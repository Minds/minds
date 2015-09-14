<?php
namespace Minds\plugin\groups\helpers;

use Minds\Core;
use Minds\Core\Data;

class Membership{

  /**
   * Return members of a group
   * @param entities\Group $group
   * @param array $options (limit, offset)
   * @return array
   */
  static public function getMembers($group, $options = array()){

    if(!$group)
      return array();

    $options = array_merge(array(
      'limit' => 12,
      'offset' => ""
    ), $options);

    $key = "$group->guid:member:inverted";

    $db = new Data\Call('relationships');
    $guids = $db->getRow($key, array('offset'=>$options['offset'], 'limit'=>$options['limit']));

    if(!$guids)
      return array();

    $users = Core\entities::get(array('guids'=>array_keys($guids)));

    return $users;
  }

  /**
   * Return the count of members in a group
   * @param entities\group $group
   * @return int
   */
  static public function getMembersCount($group){
    if(!$group)
      return 0;

    $key = "$group->guid:member:inverted";

    $db = new Data\Call('relationships');
    return $db->countRow($key);
  }

  /**
   * Return pending requests for a group
   * @param entities\Group $group
   * @param array $options (limit, offset)
   * @return array
   */
  static public function getRequests($group, $options){

    if(!$group)
      return array();

    $options = array_merge(array(
      'limit' => 12,
      'offset' => ""
    ), $options);

    $key = "$group->guid:membership_request:inverted";

    $db = new Data\Call('relationships');
    $guids = $db->getRow($key, array('offset'=>$options['offset'], 'limit'=>$options['limit']));

    if(!$guids)
      return array();

    $users = Core\entities::get(array('guids'=>array_keys($guids)));

    return $users;

  }

  /**
   * Return count of pending requests for a group
   * @param entities\Group $group
   * @return int
   */
  static public function getRequestsCount($group){
    if(!$group)
      return 0;

    $key = "$group->guid:membership_request:inverted";

    $db = new Data\Call('relationships');
    return $db->countRow($key);
  }

  /**
   * Request membership to a Group
   * @param entities\Group $group
   * @param user $user
   * @return bool
   */
  static public function requestMembership($group, $user = NULL){
    if($user == NULL)
      $user = Core\session::getLoggedinUser();

    if(!$group)
      return false;

    
  }

}
