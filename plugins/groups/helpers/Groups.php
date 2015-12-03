<?php
namespace Minds\plugin\groups\helpers;

use Minds\Core\Data;
use Minds\Core;

class Groups
{
    /**
   * Get groups a user is a member of
   */
  public static function getGroups($user, $options = array())
  {
      if (!$user) {
          $user = Core\Session::getLoggedInUser();
      }

      $options = array_merge([
        'limit' => 12,
        'offset' => ""
      ], $options);

      $key = "$user->guid:member";

      $db = new Data\Call('relationships');
      $guids = $db->getRow($key, array('offset'=>$options['offset'], 'limit'=>$options['limit']));

      if (!$guids) {
          return array();
      }

      $groups = Core\Entities::get(array('guids'=>array_keys($guids)));

      return $groups;
  }
}
