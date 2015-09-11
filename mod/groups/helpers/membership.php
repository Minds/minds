<?php
namespace Minds\plugin\groups\helpers;

use Minds\Core;
use Minds\Core\Data;

class Membership{

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

  static public function getMembersCount($group){
    if(!$group)
      return 0;

    $key = "$group->guid:member:inverted";

    $db = new Data\Call('relationships');
    return $db->countRow($key);
  }

}
