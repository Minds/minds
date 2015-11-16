<?php
/**
 * ACL Security handlers
 */
namespace Minds\Core\Security;

use Minds\Core;

class ACL
{
    private static $_;
    public static $ignore = false;

    public function __construct()
    {
    }

    /**
     * Initialise default ACL constraints
     */
    private function init()
    {
        ACL\Block::_()->listen();
    }

    public function setIgnore($ignore = false)
    {
        self::$ignore = $ignore;
    }

    /**
     * Checks access read rights to entity
     * @param Entity $entity
     * @param (optional) $user
     * @return boolean
     */
    public function read($entity, $user = null)
    {
        if (!$user) {
            $user = Core\Session::getLoggedinUser();
        }

        if (self::$ignore == true) {
            return true;
        }

        if (!Core\Session::isLoggedIn()) {
            if ((int) $entity->access_id == ACCESS_PUBLIC) {
                return true;
            } else {
                return false;
            }
        }

      /**
       * Does the user ownn the entity, or is it the container?
       */
      if ($entity->owner_guid == $user->guid || $entity->container_guid == $user->guid) {
          return true;
      }

      /**
       * Is the entity open for loggedin users?
       */
      if (in_array($entity->getAccessId(), array(ACCESS_LOGGED_IN, ACCESS_PUBLIC))) {
          return true;
      }

      /**
       * Is this user an admin?
       */
      if ($user->isAdmin()) {
          return true;
      }

      //$access_array = get_access_array($user->guid, 0);
      //if(in_array($entity->access_id, $access_array) || in_array($entity->container_guid, $access_array) || in_array($entity->guid, $access_array)){
      //  return true;
      //}

      /**
       * Allow plugins to extend the ACL check
       */
      if (Core\Events\Dispatcher::trigger('acl:read', $entity->getType(), array('entity'=>$entity, 'user'=>$user), false) === true) {
          return true;
      }

        return false;
    }

    /**
     * Checks access read rights to entity
     * @param Entity $entity
     * @param (optional) $user
     * @return boolean
     */
    public function write($entity, $user = null)
    {
        if (!$user) {
            $user = Core\Session::getLoggedinUser();
        }

        if (self::$ignore == true) {
            return true;
        }

        if (!$user) {
            return false;
        }

      /**
       * Check if we are the owner
       */
      if ($entity->owner_guid == $user->guid || $entity->container_guid == $user->guid || $entity->guid == $user->guid) {
          return true;
      }

      /**
       * Allow plugins to extend the ACL check
       */
      if (Core\Events\Dispatcher::trigger('acl:write', $entity->type, array('entity'=>$entity, 'user'=>$user), false) === true) {
          return true;
      }

        return false;
    }

    /**
     * Check if a user can interact with the entity
     * @param Entity $entity
     * @param (optional) $user
     * @return boolean
     */
    public function interact($entity, $user = null)
    {
        if (!$user) {
            $user = Core\Session::getLoggedinUser();
        }

      /**
       * Logged out users can not interact
       */
      if (!$user) {
          return false;
      }

      /**
       * Check if we are the owner
       */
      if ($entity->owner_guid == $user->guid || $entity->container_guid == $user->guid || $entity->guid == $user->guid) {
          return true;
      }

      /**
       * Allow plugins to extend the ACL check
       */
      if (Core\Events\Dispatcher::trigger('acl:interact', $entity->type, array('entity'=>$entity, 'user'=>$user), null) === false) {
          return false;
      }

        return true;
    }

    public static function _()
    {
        if (!self::$_) {
            self::$_ = new ACL();
            self::$_->init();
        }
        return self::$_;
    }
}
