<?php
/**
 * ACL Security handlers
 */
namespace Minds\Core\Security;

use Minds\Core;
use Minds\Entities;

class ACL {

    static $ignore = false;

    /**
     * Checks access read rights to entity
     * @param Entity $entity
     * @param (optional) $user
     * @return boolean
     */
    public function read($entity, $user = NULL){
      if(!$user)
        $user = Core\Session::getLoggedinUser();

      if(self::$ignore == true){
        return true;
      }

      if(!Core\Session::isLoggedIn()){
        if((int) $entity->access_id == ACCESS_PUBLIC){
          return true;
        } else {
          return false;
        }
      }

      /**
       * Does the user ownn the entity, or is it the container?
       */
      if($entity->owner_guid == $user->guid || $entity->container_guid == $user->guid){
        return true;
      }

      /**
       * Is the entity open for loggedin users?
       */
      if(in_array($entity->access_id, array(ACCESS_LOGGED_IN, ACCESS_PUBLIC))){
        return true;
      }

      /**
       * Is this user an admin?
       */
      if($user->isAdmin())
        return true;

      //$access_array = get_access_array($user->guid, 0);
      //if(in_array($entity->access_id, $access_array) || in_array($entity->container_guid, $access_array) || in_array($entity->guid, $access_array)){
      //  return true;
      //}

      /**
       * Allow plugins to extend the ACL check
       */
      if(Core\Events\Dispatcher::trigger('acl:read', $entity->type, array('entity'=>$entity, 'user'=>$user)))
        return true;

      return false;

    }

    /**
     * Checks access read rights to entity
     * @param Entity $entity
     * @param (optional) $user
     * @return boolean
     */
    public function write($entity, $user = NULL){
      if(!$user)
        $user = Core\Session::getLoggedinUser();

      if(self::$ignore == true){
        return true;
      }

      if(!$user){
        return false;
      }

      /**
       * Check if we are the owner
       */
      if($entity->owner_guid == $user->guid || $entity->container_guid == $user->guid || $entity->guid == $user->guid){
        return true;
      }

      /**
       * Allow plugins to extend the ACL check
       */
      if(Core\Events\Dispatcher::trigger('acl:write', $entity->type, array('entity'=>$entity, 'user'=>$user)))
        return true;

      return false;
    }


}
