<?php
/**
 * Minds Archive Albums API
 *
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\archive\api\v1;

use Minds\Core;
use Minds\Helpers;
use Minds\Interfaces;
use minds\plugin\archive\entities;
use Minds\Api\Factory;

class albums implements Interfaces\Api{

    /**
     * Return the archive items
     * @param array $pages
     *
     * API:: /v1/archive/albums || :guid
     */
    public function get($pages){
        $response = [];

        if(!isset($pages[0]))
            $pages = ['list'];

        switch($pages[0]){
            case "list":

                $entities = Core\Entities::get([
                  'subtype' => 'album',
                  'owner_guid' => Core\Session::getLoggedInUser()->guid
                ]);

                if(!$entities){
                  $album = new entities\album();
                  $album->title = "My Album";
                  $album->save();
                  $entities = [$album];
                }

                break;
            case "children":
            default;
                if(is_numeric($pages[0]))
                  $album_guid = $pages[0];
                else
                  $album_guid = $pages[1];

                $db = new Core\Data\Call('entities_by_time');
                $guids = $db->getRow("object:container:$album_guid", [
                  'limit' => isset($_GET['limit']) ? $_GET['limit'] : 12,
                  'offset' => isset($_GET['offset']) ? $_GET['offset'] : ""
                ]);
                if(isset($_GET['offset']))
                  unset($guids[$_GET['offset']]);

                if(!$guids){
                  return Factory::response([]);
                }

                $entities = Core\Entities::get([
                  'guids' => array_keys($guids)
                ]);

        }

        if($entities){
          $response["entities"] = Factory::exportable($entities);
          $response['load-next'] = (string) end($entities)->guid;
          $response['load-previous'] = (string) reset($entities)->guid;
        }

        return Factory::response($response);

    }

    /**
     * Create or add to an album
     * @param array $pages
     *
     * API:: /v1/archive/album | :guid
     */
    public function post($pages){

        Factory::isLoggedIn();

        if(!isset($pages[0])){

            $album = new entities\album();
            $album->title = $_POST['title'];
            $album->save();

            return Factory::response(array(
              'guid' => $guid,
              'album' => $album->export()
            ));
        }

        $album = new entities\album($pages[0]);
        $entity_guids = $_POST['guids'];
        $guids = array();
        foreach($entity_guids as $guid){
          $guids[$guid] = time();
        }
        $album->addChildren($guids);

        return Factory::response(array('status'=>'success'));

    }

    /**
     */
    public function put($pages){

        return Factory::response(array());

    }

    /**
     * Delete an album
     * @param array $pages
     *
     * API:: /v1/archive/album/:guid
     */
    public function delete($pages){

        $album = new entities\album($pages[0]);
        if($album->canEdit())
            $album->delete();

        return Factory::response();

    }

}
