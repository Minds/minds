<?php
/**
 * Management operations for Groups
 */
namespace Minds\Core\Groups;

use Minds\Core\Di\Di;

use Minds\Behaviors\Actorable;

use Minds\Entities\Group;
use Minds\Exceptions\GroupOperationException;

class Management
{
    use Actorable;

    /** @var Group $group */
    protected $group;
    protected $relDB;

    /**
     * Constructor
     */
    public function __construct($db = null, $acl = null)
    {
        $this->relDB = $db ?: Di::_()->get('Database\Cassandra\Relationships');
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
        return $this;
    }

    /**
     * Grants group ownership to a member
     * @param  mixed   $user
     * @return boolean
     */
    public function grantOwner($user)
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
     * Grants group moderation to a member
     * @param  mixed   $user
     * @return boolean
     */
    public function grantModerator($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        if (!$this->group->isOwner($this->getActor())) {
            throw new GroupOperationException('You cannot grant moderator permissions for this group');
        }

        if (!$this->group->isMember($user)) {
            throw new GroupOperationException('User is not a member');
        }

        $user_guid = is_object($user) ? $user->guid : $user;

        if ($this->getActor() && ($this->getActor()->guid == $user_guid)) {
            throw new GroupOperationException('The owner cannot be moderator');
        }

        $this->relDB->setGuid($user_guid);
        $fallback_done = $this->relDB->create('group:moderator', $this->group->getGuid());

        $this->group->pushModeratorGuid($user_guid);
        $done = $this->group->save();

        return (bool) $done;
    }

    /**
     * Revokes group moderation from a user
     * @param  mixed   $user
     * @return boolean
     */
    public function revokeModerator($user)
    {
        if (!$user) {
            throw new GroupOperationException('User not found');
        }

        if (!$this->group->isOwner($this->getActor())) {
            throw new GroupOperationException('You cannot revoke moderator permissions for this group');
        }

        $user_guid = is_object($user) ? $user->guid : $user;

        if ($this->getActor() && ($this->getActor()->guid == $user_guid)) {
            throw new GroupOperationException('You cannot revoke permissions from yourself');
        }

        $this->relDB->setGuid($user_guid);
        $fallback_done = $this->relDB->remove('group:moderator', $this->group->getGuid());

        $this->group->removeModeratorGuid($user_guid);
        $done = $this->group->save();

        return (bool) $done;
    }

    /**
     * Revokes group ownership from a user
     * @param  mixed   $user
     * @return boolean
     */
    public function revokeOwner($user)
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
}
