<?php
/**
 * Minds Group API
 *
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\groups\api\v1;

use Minds\Core;
use Minds\plugin\groups\entities;
use Minds\plugin\groups\helpers;
use Minds\Interfaces;
use Minds\Api\Factory;

class membership implements Interfaces\Api
{
    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/group/group/:guid
     */
    public function get($pages)
    {
        $group = new entities\Group($pages[0]);

        $options = array(
        'limit' => isset($_GET['limit']) ? $_GET['limit'] : 12,
        'offset' => isset($_GET['offset']) ? $_GET['offset'] : ''
      );

        if (!isset($pages[1])) {
            $pages[1] = "members";
        }

        switch ($pages[1]) {
        case "requests":
          $response = array();
          $users = helpers\Membership::getRequests($group, $options);
          if (!$users) {
              return Factory::response(array());
          }
          $response['users'] = Factory::exportable($users);
          $response['load-next'] = end($users)->user;
          break;
        case "members":
        default:
          $members = helpers\Membership::getMembers($group, $options);
          if (!$members) {
              return Factory::response(array());
          }
          $response['members'] = Factory::exportable($members);
          $response['load-next'] = end($members)->guid;
      }

        return Factory::response($response);
    }

    public function post($pages)
    {
    }

    public function put($pages)
    {
        $group = new entities\Group($pages[0]);

        if (isset($pages[1])) {
            //Admin approval
        $user = new \Minds\Entities\User($pages[1]);
            if ($group->join($user)) {
                return Factory::response(array());
            }
        }

        if ($group->join()) {
            return Factory::response(array());
        }

        return Factory::response(array(
        'status' => 'error',
        'message' => 'Could not join group'
      ));
    }

    public function delete($pages)
    {
        $group = new entities\Group($pages[0]);

        if (isset($pages[1])) {
            //Admin approval
        helpers\Membership::cancelRequest($group, new \Minds\Entities\User($pages[1]));
            return Factory::response(array());
        }

        if ($group->leave()) {
            return Factory::response(array());
        }

        return Factory::response(array(
        'status' => 'error',
        'message' => 'Could not leave group'
      ));
    }
}
