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
use minds\interfaces;
use minds\plugin\archive\entities;
use Minds\Api\Factory;

class albums implements interfaces\api, interfaces\ApiIgnorePam{

    /**
     * Return the archive items
     * @param array $pages
     *
     * API:: /v1/archive/albums || :guid
     */
    public function get($pages){
        $response = array();


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
        $entity_guids = $_POST['entity_guids'];
        $album->addChildren($entity_guids);

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
