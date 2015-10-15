<?php
/**
 * Minds Featured API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1\entities;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class featured implements Interfaces\Api{

    /**
     * Returns the entities
     * @param array $pages
     * 
     * @SWG\GET(
     *     tags={"entities"},
     *     summary="Returns featured entities",
     *     path="/v1/entities/featured/{type}/{subtype}",
     *     @SWG\Parameter(
     *      name="type",
     *      in="path",
     *      description="Type (eg. object, user, activity)",
     *      required=false,
     *      type="string"
     *     ),
     *     @SWG\Parameter(
     *      name="subtype",
     *      in="path",
     *      description="Subtype (eg. video, image, blog)",
     *      required=false,
     *      type="string"
     *     ),
     *     @SWG\Parameter(
     *      name="limit",
     *      in="query",
     *      description="Limit the number of returned entities",
     *      required=false,
     *      type="integer"
     *     ),
     *     @SWG\Parameter(
     *      name="offset",
     *      in="query",
     *      description="Pagination. Include the entity guid to start the list from",
     *      required=false,
     *      type="integer"
     *     ),
     *     @SWG\Response(name="200", description="Array")
     * )
     */      
    public function get($pages){

        $type = "object";
        $subtype = NULL;

        switch($pages[0]){
            case "video";
            case "videos":
                $subtype = "video";
                break;
            case "images":
                $subtype = "image";
                break;
            case "channels":
                $type = "user";
            case "all":
            default:
                $type = "user";
        }
        
        //the allowed, plus default, options
        $options = array(
            'type' => $type,
            'subtype' => $subtype,
            'limit'=>12,
            'offset'=>get_input('offset', '')
            );
            
        foreach($options as $key => $value){
            if(isset($_GET[$key]))
                $options[$key] = $_GET[$key];
        }

	    $key = $options['type'] . ':featured';
    	if($options['subtype'])
    		$key = $options['type'] . ':' . $options['subtype'] . ':featured';

    	$guids = core\Data\indexes::fetch($key, $options);
    	if(!$guids){
	    	return Factory::response(array('status'=>'error', 'message'=>'not found'));
    	}
        
        $options = array('guids'=>$guids);
        $entities = core\Entities::get($options);

        usort($entities, function($a, $b){
          if ((int)$a->featured_id == (int) $b->featured_id) {
            return 0;
          }
          return ((int)$a->featured_id < (int)$b->featured_id) ? 1 : -1;
        });

        if($entities){
            $response['entities'] = factory::exportable($entities);
            $response['load-next'] = (string) end($entities)->featured_id;
            $response['load-previous'] = (string) key($entities)->featured_id;
        }
        
        return Factory::response($response);
        
    }
    
    public function post($pages){}
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
