<?php
/**
 * Membership operations for Groups
 * Handles joining, leaving, banning, and related operations.
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Security\ACL;
use Minds\Core\Entities;
use Minds\Entities\User;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class Membership
{
    protected $group;
    protected $relDB;
    protected $notifications;
    protected $acl;

    /**
     * Constructor
     * @param GroupEntity $group
     */
    public function __construct(GroupEntity $group, $db = null, $notifications = null, $acl = null)
    {
        $this->group = $group ?: new GroupEntity();
        $this->relDB = $db ?: Di::_()->get('Database\Cassandra\Relationships');
        $this->notifications = $notifications ?: new Notifications($this->group);
        $this->acl = $acl ?: ACL::_();
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
    public function getMembersCount()
    {
        $this->relDB->setGuid($this->group->getGuid());

        return $this->relDB->countInverse('member');
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
    public function getRequestsCount()
    {
        $this->relDB->setGuid($this->group->getGuid());

        return $this->relDB->countInverse('membership_request');
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
            'force' => false,
            'actor' => $user
        ], $opts);

        if (!$user) {
            return false;
        }

        if ($opts['actor'] && !($opts['actor'] instanceof User)) {
            $opts['actor'] = EntitiesFactory::build($opts['actor']);
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);
        $canJoin = $this->group->isPublic();

        if (!$canJoin && $opts['actor']) {
            $canJoin = $this->acl->write($this->group, $opts['actor']);
        }

        if ($opts['force'] || $canJoin) {
            $this->cancel($user_guid);
            return $this->relDB->create('member', $this->group->getGuid());
        }

        return $this->relDB->create('membership_request', $this->group->getGuid());
    }

    /**
     * Removes an user from the group members list
     * @param  mixed   $user
     * @return boolean
     */
    public function leave($user)
    {
        if (!$user) {
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        $this->notifications->unmute($user_guid);

        return $this->relDB->remove('member', $this->group->getGuid());
    }

    /**
     * Kicks an user from the group members list. Sends a notification.
     * @param  mixed   $user
     * @return boolean
     */
    public function kick($user)
    {
        if (!$user) {
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $left = $this->leave($user);

        if ($left) {
            $this->notifications->sendKickNotification($user_guid);
        }

        return $left;
    }

    /**
     * Checks if a user is on the group member list
     * @param  mixed   $user
     * @return boolean
     */
    public function isMember($user)
    {
        if (!$user) {
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        return $this->relDB->check('member', $this->group->getGuid());
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
    public function cancel($user)
    {
        if (!$user) {
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        return $this->relDB->remove('membership_request', $this->group->getGuid());
    }

    /**
     * Prevents a user from becoming a member of the group
     * @param  mixed   $user
     * @return boolean
     */
    public function ban($user, $actor)
    {
        if (!$user) {
            return false;
        }

        if ($actor && !($actor instanceof User)) {
            $actor = EntitiesFactory::build($actor);
        }

        if (!$actor || !$this->acl->write($this->group, $actor)) {
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $actor_guid = is_object($actor) ? $actor->guid : $actor;
        $this->relDB->setGuid($user_guid);

        if ($actor_guid == $user_guid) {
            // Cannot self-ban
            return false;
        }

        if ($this->isMember($user)) {
            $kicked = $this->kick($user);

            if (!$kicked) {
                return false;
            }
        }

        return $this->relDB->create('group:banned', $this->group->getGuid());
    }

    /**
     * Removes a user banning
     * @param  mixed   $user
     * @return boolean
     */
    public function unban($user, $actor)
    {
        if (!$user) {
            return false;
        }

        if ($actor && !($actor instanceof User)) {
            $actor = EntitiesFactory::build($actor);
        }

        if (!$actor || !$this->acl->write($this->group, $actor)) {
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $this->relDB->setGuid($user_guid);

        return $this->relDB->remove('group:banned', $this->group->getGuid());
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

}
