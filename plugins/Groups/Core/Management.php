<?php
/**
 * Management operations for Groups
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Di\Di;
use Minds\Core\Security\ACL;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Entities\User;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

use Minds\Plugin\Groups\Behaviors\Actorable;

use Minds\Plugin\Groups\Exceptions\GroupOperationException;

class Management
{
    use Actorable;

    protected $group;
    protected $relDB;
    protected $acl;

    /**
     * Constructor
     * @param GroupEntity $group
     */
    public function __construct(GroupEntity $group, $db = null, $acl = null)
    {
        $this->group = $group;
        $this->relDB = $db ?: Di::_()->get('Database\Cassandra\Relationships');
        $this->setAcl($acl);
    }

    /**
     * Grants group ownership to a member
     * @param  mixed   $user
     * @return boolean
     */
    public function grant($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        if (!$this->canActorWrite($this->group)) {
            throw new GroupOperationException('You cannot grant permissions for this group');
        }

        if (!$this->group->isMember($user)) {
            throw new GroupOperationException('User is not a member');
        }

        $user_guid = is_object($user) ? $user->guid : $user;

        $this->relDB->setGuid($user_guid);
        $fallback_done = $this->relDB->create('group:owner', $this->group->getGuid());

        $this->group->pushOwnerGuid($user_guid);
        $done = $this->group->save();

        return (bool) $done;
    }

    /**
     * Revokes group ownership from a user
     * @param  mixed   $user
     * @return boolean
     */
    public function revoke($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        if (!$this->canActorWrite($this->group)) {
            throw new GroupOperationException('You cannot revoke permissions for this group');
        }

        $user_guid = is_object($user) ? $user->guid : $user;

        if ($this->getActor() && ($this->getActor()->guid == $user_guid)) {
            throw new GroupOperationException('You cannot revoke permissions from yourself');
        }

        $this->relDB->setGuid($user_guid);
        $fallback_done = $this->relDB->remove('group:owner', $this->group->getGuid());

        $this->group->removeOwnerGuid($user_guid);
        $done = $this->group->save();

        return (bool) $done;
    }

    /**
     * Checks if the user owns the group. Used by ACL event.
     * @param  mixed   $user
     * @return boolean
     */
    public function isOwner($user)
    {
        if (!$user) {
            return false;
        }

        if ($this->isCreator($user)) {
            return true;
        }

        $user_guid = is_object($user) ? $user->guid : $user;

        return $this->isCreator($user) || in_array($user_guid, $this->group->getOwnerGuids());
    }

    /**
     * Checks if the user is the creator of the group. Used by ACL event.
     * @param  mixed   $user
     * @return boolean
     */
    public function isCreator($user)
    {
        if (!$user) {
            return false;
        }

        $user_guid = is_object($user) ? $user->guid : $user;
        $owner = $this->group->getOwnerObj();

        if (!$owner) {
            return false;
        }

        return $user_guid == $owner->guid;
    }
}
