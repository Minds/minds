<?php
/**
 * Invitations to Groups
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Di\Di;
use Minds\Core\Security\ACL;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Entities;
use Minds\Core\Queue\Client as QueueClient;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Entities\User as User;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;
use Minds\Plugin\Groups\Core\Membership as CoreMembership;

class Invitations
{
    protected $relDB;
    protected $group;

    /**
     * Constructor
     * @param GroupEntity $group
     */
    public function __construct(GroupEntity $group, $db = null, $acl = null, $friendsDB = null)
    {
        $this->group = $group;
        $this->relDB = $db ?: Di::_()->get('Database\Cassandra\Relationships');
        // TODO: [emi] Ask Mark about a 'friendsof' replacement (or create a DI entry)
        $this->friendsDB = $friendsDB ?: new \Minds\Core\Data\Call('friendsof');
        $this->acl = $acl ?: ACL::_();
    }

    /**
     * Fetch the group invitations
     * @return array
     */
    public function getInvitations(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => '',
            'hydrate' => true
        ], $opts);

        $this->relDB->setGuid($this->group->getGuid());

        $guids = $this->relDB->get('group:invited', [
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
     * Checks a GUID array for invitation status
     * @param  array   $users
     * @return boolean
     */
    public function isInvitedBatch(array $users = [])
    {
        if (!$users) {
            return [];
        }

        $invited_guids = $this->getInvitations([ 'hydrate' => false ]);
        $result = [];

        foreach ($users as $user) {
            $result[$user] = in_array($user, $invited_guids);
        }

        return $result;
    }

    /**
     * Checks invitation status
     * @param  mixed   $invitee
     * @return boolean
     */
    public function isInvited($invitee)
    {
        if (!$invitee) {
            return false;
        }

        $invitee_guid = is_object($invitee) ? $invitee->guid : $invitee;
        $this->relDB->setGuid($invitee_guid);

        return $this->relDB->check('group:invited', $this->group->getGuid());
    }

    /**
     * Invites a user to the group
     * @param  mixed   $invitee
     * @param  mixed   $from
     * @return boolean
     */
    public function invite($invitee, $actor, array $opts = [])
    {
        $opts = array_merge([
            'notify' => true
        ], $opts);

        if (!$invitee) {
            return false;
        }

        if (!$actor) {
            return false;
        }

        if ($actor && !($actor instanceof User)) {
            $actor = EntityFactory::build($actor);
        }

        $canInvite = $this->userCanInvite($actor, $invitee);

        if (!$canInvite) {
            return false;
        }

        $invitee_guid = is_object($invitee) ? $invitee->guid : $invitee;
        // TODO: [emi] Check if the user blocked this group from sending invites
        $this->relDB->setGuid($invitee_guid);

        $invited = $this->relDB->create('group:invited', $this->group->getGuid());

        if ($opts['notify']) {
            Dispatcher::trigger('notification', 'all', [
                'to' => [ $invitee_guid ],
                'notification_view' => 'group_invite',
                'params' => [
                    'group' => $this->group->export(),
                    'user' => $actor->username
                ]
            ]);
        }

        return $invited;
    }

    /**
     * Destroys a user invitation to the group
     * @param  mixed   $invitee
     * @param  mixed   $from
     * @return boolean
     */
    public function uninvite($invitee, $actor)
    {
        if (!$invitee) {
            return false;
        }

        if (!$actor) {
            return false;
        }

        if ($actor && !($actor instanceof User)) {
            $actor = EntityFactory::build($actor);
        }

        $canInvite = $this->userCanInvite($actor, $invitee);

        if (!$canInvite) {
            return false;
        }

        return $this->removeInviteFromIndex($invitee);
    }

    /**
     * Accepts an invitation to the group
     * @param  mixed   $invitee
     * @return boolean
     */
    public function accept($invitee)
    {
        if (!$invitee) {
            return false;
        }

        if (!$this->isInvited($invitee)) {
            return false;
        }

        $this->removeInviteFromIndex($invitee);
        return $this->group->join($invitee, [ 'force' => true ]);
    }

    /**
     * Declines an invitation to the group
     * @param  mixed   $invitee
     * @return boolean
     */
    public function decline($invitee)
    {
        if (!$invitee) {
            return false;
        }

        $this->removeInviteFromIndex($invitee);
        return true;
    }

    /**
     * Checks if the user can invite to the group. It'll optionally check if it can invite a certain user.
     * @param  mixed   $user
     * @param  mixed   $invitee Optional.
     * @return boolean
     */
    public function userCanInvite($user, $invitee = null)
    {
        if ($user && !($user instanceof User)) {
            $user = EntityFactory::build($user);
        }

        if ($invitee && !($user instanceof User)) {
            $invitee = EntityFactory::build($invitee);
        }

        if ($user->isAdmin()) {
            return true;
        } elseif ($this->group->isPublic() && $this->group->isMember($user)) {
            return $invitee ? $this->userHasSubscriber($user, $invitee) : true;
        } elseif (!$this->group->isPublic() && $this->acl->write($this->group, $user)) {
            return $invitee ? $this->userHasSubscriber($user, $invitee) : true;
        }

        return false;
    }

    /**
     * Checks if a user has a certain subscriber
     * @param  User   $user
     * @param  User   $subscriber
     * @return boolean
     */
    public function userHasSubscriber(User $user, User $subscriber)
    {
        $row = $this->friendsDB->getRow($user->guid, [ 'limit' => 1, 'offset' => (string) $subscriber->guid ]);

        return $row && isset($row[(string) $subscriber->guid]);
    }

    /**
     * Shrotcut function to remove a GUID from the "group:invited" index.
     * @param  mixed $invitee
     * @return boolean
     */
    protected function removeInviteFromIndex($invitee)
    {
        $invitee_guid = is_object($invitee) ? $invitee->guid : $invitee;
        $this->relDB->setGuid($invitee_guid);

        return $this->relDB->remove('group:invited', $this->group->getGuid());
    }
}
