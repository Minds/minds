<?php
/**
 * Minds Trending API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1\entities;

use Minds\Core;
use minds\entities;
use minds\interfaces;
use minds\api\factory;

class trending implements interfaces\api{

    /**
     * Returns the entities
     * @param array $pages
     * 
     * API:: /v1/entities/ or /v1/entities/all
     */      
    public function get($pages){
        //temp hack..
        if(isset($pages[0]) && $pages[0] == 'video')
            $pages[0] = 'kaltura_video';

        //the allowed, plus default, options
        $options = array(
            'type' => 'object',
            'subtype' => isset($pages[0]) ? $pages[0] : NULL,
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
            return factory::response(array('status'=>'error', 'message'=>'not found'));
        }
	$options['guids'] = $guids;
	$entities = core\entities::get($options);
        
        if($entities){
            $response['entities'] = factory::exportable($entities);
            $response['load-next'] = isset($_GET['load-next']) ? count($entities) + $_GET['load-next'] : count($entities);
            $response['load-previous'] = isset($_GET['load-previous']) ? $_GET['load-previous'] - count($entities) : 0;
        }
        
        return factory::response($response);
        
    }
    
    public function post($pages){}
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
