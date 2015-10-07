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
use minds\interfaces;
use Minds\Api\Factory;

class group implements Interfaces\api{

    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/group/group/:guid
     */
    public function get($pages){

        $group = new entities\Group($pages[0]);
        $response['group'] = $group->export();
        $response['group']['members'] = Factory::exportable(helpers\Membership::getMembers($group));
        $response['group']['members:count'] = helpers\Membership::getMembersCount($group);
        $response['group']['requests'] = array();
        $response['group']['requests:count'] = helpers\Membership::getRequestsCount($group);

        return Factory::response($response);

    }

    public function post($pages){

      if(isset($pages[0])){
        $group = new entities\Group($pages[0]);
      } else {
        $group = new entities\Group();
      }

      $group->name = $_POST['name'];
      $group->access_id = 2;
      $group->membership = $_POST['membership'];
      $group->save();
      //now join
      $group->join(Core\Session::getLoggedInUser());

      $response = array();
      $response['guid'] = $group->guid;

      return Factory::response($response);
    }

    public function put($pages){
        return Factory::response(array());
    }

    public function delete($pages){
        return Factory::response(array());
    }

}
