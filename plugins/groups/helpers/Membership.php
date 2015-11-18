<?php
namespace Minds\plugin\groups\helpers;

use Minds\Core;
use Minds\Core\Data;

class Membership
{
    /**
   * Return members of a group
   * @param entities\Group $group
   * @param array $options (limit, offset)
   * @return array
   */
  public static function getMembers($group, $options = array())
  {
      if (!$group) {
          return array();
      }

      $options = array_merge(array(
        'limit' => 12,
        'offset' => ""
    ), $options);

      $key = "$group->guid:member:inverted";

      $db = new Data\Call('relationships');
      $guids = $db->getRow($key, array('offset'=>$options['offset'], 'limit'=>$options['limit']));

      if (!$guids) {
          return array();
      }

      $users = Core\Entities::get(array('guids'=>array_keys($guids)));

      return $users;
  }

  /**
   * Return the count of members in a group
   * @param Entities\Group $group
   * @return int
   */
  public static function getMembersCount($group)
  {
      if (!$group) {
          return 0;
      }

      return Data\Relationships::build()->count($group->guid, "member", true);
  }

  /**
   * Return pending requests for a group
   * @param entities\Group $group
   * @param array $options (limit, offset)
   * @return array
   */
  public static function getRequests($group, $options)
  {
      if (!$group) {
          return array();
      }

      $options = array_merge(array(
          'limit' => 12,
          'offset' => ""
      ), $options);

      $key = "$group->guid:membership_request:inverted";

      $db = new Data\Call('relationships');
      $guids = $db->getRow($key, array('offset'=>$options['offset'], 'limit'=>$options['limit']));

      if (!$guids) {
          return array();
      }

      $users = Core\Entities::get(array('guids'=>array_keys($guids)));

      return $users;
  }

  /**
   * Return count of pending requests for a group
   * @param entities\Group $group
   * @return int
   */
  public static function getRequestsCount($group)
  {
      if (!$group) {
          return 0;
      }

      return Data\Relationships::build()->count($group->guid, "membership_request", true);
  }

  /**
   * Join or Request membership to a Group
   * @param entities\Group $group
   * @param user $user
   * @return bool
   */
  public static function join($group, $user = null)
  {
      if ($user == null) {
          $user = Core\Session::getLoggedinUser();
      }

      if (!$group) {
          return false;
      }

      if ($group->membership == 2 || $group->canEdit()) {
          Data\Relationships::build()->remove($user->guid, 'membership_request', $group->guid);
          return Data\Relationships::build()->create($user->guid, 'member', $group->guid);
      }

      return Data\Relationships::build()->create($user->guid, 'membership_request', $group->guid);
  }

  /**
   * Leave a group
   * @param entities\Group $group
   * @param user $user
   * @return bool
   */
  public static function leave($group, $user = null)
  {
      if ($user == null) {
          $user = Core\Session::getLoggedinUser();
      }

      if (!$group) {
          return false;
      }

      return Data\Relationships::build()->remove($user->guid, 'member', $group->guid);
  }

  /**
   * Check if a user is a member
   * @param entities\Group | string $group
   * @param user  | string $user
   * @return bool
   */
  public static function isMember($group, $user = null)
  {
      if ($user == null) {
          $user = Core\Session::getLoggedinUser();
      }

      if (!$user) {
          return false;
      }

      $user_guid = $user;
      if (is_object($user)) {
          $user_guid = $user->guid;
      }

      if (!$group) {
          return false;
      }

      $group_guid = $group;
      if (is_object($group)) {
          $group_guid = $group->guid;
      }

      return Data\Relationships::build()->check($user_guid, 'member', $group_guid);
  }

    public static function cancelRequest($group, $user = null)
    {
        if ($user == null) {
            $user = Core\Session::getLoggedinUser();
        }

        if (!$group) {
            return false;
        }

        return Data\Relationships::build()->remove($user->guid, 'membership_request', $group->guid);
    }
}
