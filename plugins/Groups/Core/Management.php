<?php
/**
 * Management operations for Groups
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Di\Di;
use Minds\Core\Security\ACL;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class Management
{
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
        $this->acl = $acl ?: ACL::_();
    }

    /**
     * Grants group ownership to a member
     * @param  mixed   $user
     * @param  mixed   $actor
     * @return boolean
     */
    public function grant($user, $actor)
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

        if (!$this->group->isMember($user)) {
            return false;
        }

        $this->relDB->setGuid($user_guid);
        $fallback_done = $this->relDB->create('group:owner', $this->group->getGuid());

        $this->group->pushOwnerGuid($user_guid);
        $done = $this->group->save();

        return (bool) $done;
    }

    /**
     * Revokes group ownership from a user
     * @param  mixed   $user
     * @param  mixed   $actor
     * @return boolean
     */
    public function revoke($user, $actor)
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

        if ($actor_guid == $user_guid) {
            // Cannot self-revoke
            return false;
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
