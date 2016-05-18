<?php
/**
 * Membership operations for Groups
 * Handles joining, leaving, banning, and related operations.
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Entities;
use Minds\Entities\User;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

use Minds\Plugin\Groups\Behaviors\Actorable;

use Minds\Plugin\Groups\Exceptions\GroupOperationException;

class Membership
{
    use Actorable;

    static private $_ = [];

    protected $group;
    protected $relDB;
    protected $cache;
    protected $notifications;

    /**
     * Constructor
     * @param GroupEntity $group
     */
    public function __construct($db = null, $notifications = null, $acl = null, $cache = null)
    {
        $this->relDB = $db ?: Di::_()->get('Database\Cassandra\Relationships');
        $this->notifications = $notifications ?: new Notifications;
        $this->cache = $cache ?: Di::_()->get('Cache');
        $this->setAcl($acl);
    }

    /**
     * Set the group
     * @param Group $group
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;
        $this->notifications->setGroup($group);
        return $this;
    }

    /**
     * Fetch the group members
     * @param  array $opts
     * @return array
     */
    public function getMembers(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => '',
            'hydrate' => true
        ], $opts);

        $this->relDB->setGuid($this->group->getGuid());

        $guids = $this->relDB->get('member', [
            'limit' => $opts['limit'],
            'offset' => $opts['offset'],
            'inverse' => true
        ]);

        if($opts['offset']){
            array_shift($guids);
        }

        if (!$guids) {
            return [];
        }

        if (!$opts['hydrate']) {
            return $guids;
        }

        $users = Entities::get([ 'guids' => $guids ]);

        return $users;
    }

    /**
     * Count the group members
     * @return int
     */
    public function getMembersCount($cached = true)
    {
        if(($count = $this->cache->get("group:{$this->group->getGuid()}:members:count")) !== FALSE){
            return $count;
        }
        $this->relDB->setGuid($this->group->getGuid());
        
        $count = $this->relDB->countInverse('member');
        $this->cache->set("group:{$this->group->getGuid()}:members:count", $count);
        return $count;
    }

    /**
     * Fetch the group's membership requests
     * @param  array $opts
     * @return array
     */
    public function getRequests(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => '',
            'hydrate' => true
        ], $opts);

        $this->relDB->setGuid($this->group->getGuid());

        $guids = $this->relDB->get('membership_request', [
            'limit' => $opts['limit'],
            'offset' => $opts['offset'],
            'inverse' => true
        ]);

        if (!$guids) {
            return [];
        }

        if (!$opts['hydrate']) {
            return $guids;
        }

        $users = Entities::get([ 'guids' => $guids ]);

        return $users;
    }

    /**
     * Count the group's membership requests
     * @return int
     */
    public function getRequestsCount($cached = true)
    {
        if($count = $this->cache->get("group:{$this->group->getGuid()}:requests:count")){
            return $count;
        }
        $this->relDB->setGuid($this->group->getGuid());

        $count = $this->relDB->countInverse('membership_request');
        $this->cache->set("group:{$this->group->getGuid()}:requests:count", $count);
        return $count;
    }

    public function acceptAllRequests()
    {
        $offset = '';
        $count = 0;

        while (true) {
            $guids = $this->getRequests([ 'hydrate' => false, 'limit' => 500, 'offset' => $offset ]);

            if (!$guids) {
                break;
            }

            if ($offset == $guids[0]) {
                break;
            }

            $offset = end($guids);

            foreach ($guids as $guid) {
                $this->relDB->setGuid($guid);
                $this->relDB->create('member', $this->group->getGuid());
                $this->relDB->remove('membership_request', $this->group->getGuid());

                $this->cache->destroy("group:{$this->group->getGuid()}:members:count");
                $this->cache->destroy("group:{$this->group->getGuid()}:requests:count");

                $count++;
            }
        }

        return $count;
    }

    /**
     * Adds an user to group members list
     * @param  mixed   $user
     * @param  array   $opts
     * @return boolean
     */
    public function join($user, array $opts = [])
    {
        $opts = array_merge([
            'force' => false
        ], $opts);

        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        if ($this->isMember($user, false)) {
            throw new GroupOperationException('User is already a member');
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $banned = $this->isBanned($user);
        $canJoin = $this->group->isPublic() || $this->group->isInvited($user_guid);

        if (!$canJoin && $this->hasActor()) {
            $canJoin = $this->canActorWrite($this->group);

            // Unban if the actor has write capabilities
            if ($canJoin && $banned) {
                $unbanned = $this->unban($user);
                $banned = !$unbaned;
            }
        }

        if ($banned) {
            throw new GroupOperationException('You are banned from this group');
        }

        $this->relDB->setGuid($user_guid);
        $done = false;

        if ($opts['force'] || $canJoin) {
            if ($this->isAwaiting($user_guid)) {
                try {
                    $this->cancelRequest($user_guid);
                } catch (GroupOperationException $e) { }
            }

            $done = $this->relDB->create('member', $this->group->getGuid());
            $this->cache->set("group:{$this->group->getGuid()}:isMember:$user_guid", true);
        } else {
            $done = $this->relDB->create('membership_request', $this->group->getGuid());
        }

        $this->cache->destroy("group:{$this->group->getGuid()}:members:count");
        $this->cache->destroy("group:{$this->group->getGuid()}:requests:count");

        // TODO: [emi] Send a notification to target user if was awaiting

        if ($done) {
            return true;
        }

        throw new GroupOperationException('Error joining group');
    }

    /**
     * Removes an user from the group members list
     * @param  mixed   $user
     * @return boolean
     */
    public function leave($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        if (!$this->isMember($user, false)) {
            throw new GroupOperationException('User is not a member');
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        $this->notifications->unmute($user_guid);

        $done = $this->relDB->remove('member', $this->group->getGuid());

        $this->cache->destroy("group:{$this->group->getGuid()}:members:count");
        $this->cache->destroy("group:{$this->group->getGuid()}:requests:count");
        $this->cache->set("group:{$this->group->getGuid()}:isMember:$user_guid", false);

        if ($done) {
            return true;
        }

        throw new GroupOperationException('Error leaving group');
    }

    /**
     * Kicks an user from the group members list. Sends a notification.
     * @param  mixed   $user
     * @return boolean
     */
    public function kick($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        if (!$this->canActorActUponUser($user, $this->group, false)) {
            throw new GroupOperationException('You cannot kick this user');
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->leave($user);

        return true;
    }

    /**
     * Checks if a user is on the group member list
     * @param  mixed   $user
     * @return boolean
     */
    public function isMember($user, $cache = true)
    {
        if (!$user) {
            return false;
        }
 
        $user_guid = is_object($user) ? $user->guid : $user;

        if($cache && ($is = $this->cache->get("group:{$this->group->getGuid()}:isMember:$user_guid")) !== FALSE){
            return (bool) $is;
        }
        
        $this->relDB->setGuid($user_guid);

        $is = $this->relDB->check('member', $this->group->getGuid());
        $this->cache->set("group:{$this->group->getGuid()}:isMember:$user_guid", $is);
        return $is;
    }

    /**
     * Checks if a user is awaiting to join the group
     * @param  mixed   $user
     * @return boolean
     */
    public function isAwaiting($user)
    {
        if (!$user) {
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        return $this->relDB->check('membership_request', $this->group->getGuid());
    }


    /**
     * Cancel a user's membership request to the group
     * @param  mixed  $user
     * @return boolean
     */
    public function cancelRequest($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        if (!$this->canActorActUponUser($user, $this->group)) {
            throw new GroupOperationException('You cannot cancel this request');
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        $cancelled = $this->relDB->remove('membership_request', $this->group->getGuid());
        $this->cache->destroy("group:{$this->group->getGuid()}:requests:count");

        if ($cancelled) {
            return true;
        }

        throw new GroupOperationException('Error cancelling request');
    }

    /**
     * Prevents a user from becoming a member of the group
     * @param  mixed   $user
     * @return boolean
     */
    public function ban($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        $user_guid = is_object($user) ? $user->guid : $user;

        if ($this->isActorUser($user_guid)) {
            throw new GroupOperationException('You cannot ban yourself');
        }

        if (!$this->canActorActUponUser($user, $this->group, false)) {
            throw new GroupOperationException('You cannot ban this user');
        }

        $this->relDB->setGuid($user_guid);

        $banned = $this->relDB->create('group:banned', $this->group->getGuid());

        if ($this->isMember($user)) {
            $this->kick($user);
        }

        if ($banned) {
            return true;
        }

        throw new GroupOperationException('Cannot ban user');
    }

    /**
     * Removes a user banning
     * @param  mixed   $user
     * @return boolean
     */
    public function unban($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        if (!$this->canActorWrite($this->group)) {
            throw new GroupOperationException('You cannot unban this user');
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        $unbanned = $this->relDB->remove('group:banned', $this->group->getGuid());

        if ($unbanned) {
            return true;
        }

        throw new GroupOperationException('Cannot unban user');
    }

    /**
     * Check if a user is on the banned list
     * @param  mixed   $user
     * @return boolean
     */
    public function isBanned($user)
    {
        if (!$user) {
            return false;
        }
        
        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        return $this->relDB->check('group:banned', $this->group->getGuid());
    }

    /**
     * Gets the GUIDs of banned users
     * @param  array $opts
     * @return array
     */
    public function getBannedUsers(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => '',
            'hydrate' => true
        ], $opts);

        $this->relDB->setGuid($this->group->getGuid());

        $guids = $this->relDB->get('group:banned', [
            'limit' => $opts['limit'],
            'offset' => $opts['offset'],
            'inverse' => true
        ]);

        if (!$guids) {
            return [];
        }

        if (!$opts['hydrate']) {
            return $guids;
        }

        $users = Entities::get([ 'guids' => $guids ]);

        return $users;
    }

    /**
     * Gets the banned status for passed users
     * @param  array   $users
     * @return array
     */
    public function isBannedBatch(array $users = [])
    {
        if (!$users) {
            return [];
        }

        $banned_guids = $this->getBannedUsers([ 'hydrate' => false ]);
        $result = [];

        foreach ($users as $user) {
            $result[$user] = in_array($user, $banned_guids);
        }

        return $result;
    }

    /**
     * Destroys the group's indexes
     * @return boolean
     */
    public static function cleanup($group, $db = null)
    {
        $db = $db ?: Di::_()->get('Database\Cassandra\Relationships');

        $db->setGuid($group['guid']);

        $done = $db->destroy('member', [ 'inverse' => true ]);
        $done = $done && $db->destroy('membership_request', [ 'inverse' => true ]);

        return $done;
    }


    public static function _($group)
    {
        if(!isset(self::$_[$group->guid])){
            self::$_[$group->guid] = (new Membership)->setGroup($group);
        }
        return self::$_[$group->guid];
    }
}
