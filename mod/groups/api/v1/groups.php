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

class groups implements interfaces\api{

    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/groups/:filter
     */
    public function get($pages){

        $e = new Core\Data\Call('entities');
        $r = new Core\Data\Call('relationships');

        if(!isset($pages[0]))
          $pages[0] = "featured";

        switch($pages[0]){
          case "featured":
            //$guids = $db->getRow("")
            break;
          case "member":
            $groups = helpers\Groups::getGroups(Core\Session::getLoggedInUser(), array(
              'limit' => 12,
              'offset' => isset($_GET['offset']) ? $_GET['offset'] : ''
            ));
            break;
          case "all":
          default:
            $groups = Core\Entities::get(array(
              'type' => 'group'
            ));
        }

        $response['groups'] = Factory::exportable($groups);
        $response['load-next'] = end($groups)->guid;

        return Factory::response($response);

    }

    public function post($pages){

    }

    public function put($pages){
        return Factory::response(array());
    }

    public function delete($pages){
        return Factory::response(array());
    }

}
