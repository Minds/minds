<?php
/**
 * Minds Blog API
 *
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\blog\api\v1;

use Minds\Core;
use minds\plugin\blog\entities;
use minds\interfaces;
use Minds\Api\Factory;

class blog implements interfaces\api{

    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/blog/:filter
     */
    public function get($pages){

        $response = array();

        if(!isset($pages[0]))
          $pages[0] = "featured";

        switch($pages[0]){
          case "featured":
            $guids = Core\Data\indexes::fetch('object:blog:featured', array('offset'=> isset($_GET['offset']) ? $_GET['offset'] : "", 'limit'=> isset($_GET['limit']) ? $_GET['limit'] : 12 ));
  				  if(!$guids)
              break;
  				  $entities = core\entities::get(array('guids'=>$guids));
            usort($entities, function($a, $b){
    					if ((int)$a->featured_id == (int) $b->featured_id) { 
    					   return 0;
    					 }
    					return ((int)$a->featured_id < (int)$b->featured_id) ? 1 : -1;
    				});
            $response['blogs'] = Factory::exportable($entities);
            $response['load-next'] = end($entities)->featured_id;
            break;
          case "trending":
            break;
          case "owner":
            $entities = core\entities::get(array(
              'subtype' => 'blog',
              'owner_guid' => isset($pages[1]) ? $pages[1] : \Minds\Core\session::getLoggedInUser()->guid
            ));
            $response['blogs'] = Factory::exportable($entities);
            $response['load-next'] = end($entities)->guid;
            break;
          case is_numeric($pages[0]):
            $blog = new entities\Blog($pages[0]);
            $response['blog'] = $blog->export();
            break;
        }


        return Factory::response($response);
    }

    public function post($pages){

        $response = array();

        if(isset($pages[0]) && is_numeric($pages[0]))
          $blog = new entities\Blog($pages[0]);
        else
          $blog = new entities\Blog();

        $allowed = array('title', 'description', 'access_id', 'status');

        foreach($allowed as $v){
          if(isset($_POST[$v]))
            $blog->$v = $_POST[$v];
        }

        $blog->save();

        $response['guid'] = $blog->guid;

        return Factory::response($response);

    }

    public function put($pages){


        return Factory::response(array());

    }

    public function delete($pages){

        return Factory::response(array());

    }

}
