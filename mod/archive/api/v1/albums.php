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
        $response = array();
        $album_guid = $pages[0];

        $db = new Core\Data\Call('entities_by_time');
        $guids = $db->getRow("object:container:$album_guid", array(
          'limit' => isset($_GET['limit']) ? $_GET['limit'] : 12,
          'offset' => isset($_GET['offset']) ? $_GET['offset'] : ""
        ));

        $entities = Core\Entities::get(array(
          'guids' => array_keys($guids)
        ));

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

         return Factory::response();

    }

}
