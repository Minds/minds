<?php
/**
 * Management operations for Groups
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Di\Di;
use Minds\Core\Security\ACL;
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
