<?php
/**
 * Minds Trending API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1\entities;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class trending implements Interfaces\Api{

    /**
     * Returns the entities
     * @param array $pages
     * 
     * @SWG\GET(
     *     tags={"entities"},
     *     summary="Returns trending entities",
     *     path="/v1/entities/trending/{type}/{subtype}",
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
        //temp hack..
        //if(isset($pages[1]) && $pages[1] == 'video')
          //  $pages[1] = 'kaltura_video';

        switch($pages[1]){
            case 'image':
            case 'images':
                $prepared = new Core\Data\Neo4j\Prepared\Common();
                $result= Core\Data\Client::build('Neo4j')->request($prepared->getTrendingObjects('image', get_input('offset', 0)));
                $rows = $result->getRows();
                
                $guids = array();
                foreach($rows['object'] as $object){
                    $guids[] = $object['guid'];
                } 
                $entities = core\Entities::get(array('guids'=>$guids));       
                break;
            case 'videos':
            case 'video':
                $prepared = new Core\Data\Neo4j\Prepared\Common();
               $result= Core\Data\Client::build('Neo4j')->request($prepared->getTrendingObjects('video', get_input('offset', 0)));
                $rows = $result->getRows();
                
                $guids = array();
                foreach($rows['object'] as $object){
                    $guids[] = $object['guid'];
                } 
                $entities = core\Entities::get(array('guids'=>$guids));       
                break;
            default:
                
                if(!$guids){
                    return Factory::response(array('status'=>'error', 'message'=>'not found'));
                }
                $options['guids'] = $guids;
                $entities = core\Entities::get($options);


        }

        /*if(!$entities){
        //the allowed, plus default, options
        $options = array(
            'type' => isset($pages[0]) ? $pages[0] : 'object',
            'subtype' => isset($pages[1]) ? $pages[1] : NULL,
            'limit'=>12,
            'offset'=>''
            );
            
        foreach($options as $key => $value){
            if(isset($_GET[$key]))
                $options[$key] = $_GET[$key];
        }
        
       
	    $opts = array('timespan' => get_input('timespan', 'day'));
    	$trending = new \MindsTrending(null, $opts);
    	$guids = $trending->getList($options);
    	if(!$guids){
            return Factory::response(array('status'=>'error', 'message'=>'not found'));
        }
    	$options['guids'] = $guids;
    	$entities = core\Entities::get($options);
        }
         */
        if($entities){
            $response['entities'] = factory::exportable($entities);
            $response['load-next'] = isset($_GET['load-next']) ? count($entities) + $_GET['load-next'] : count($entities);
            $response['load-previous'] = isset($_GET['load-previous']) ? $_GET['load-previous'] - count($entities) : 0;
        }
        
        return Factory::response($response);
        
    }
    
    public function post($pages){}
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
