<?php
/**
 * Minds Trending API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1\entities;

use minds\core;
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
        
        //the allowed, plus default, options
        $options = array(
            'type' => 'object',
            'subtype' => NULL,
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
            $response['load-next'] = (string) end($entities)->guid;
            $response['load-previous'] = (string) key($entities)->guid;
        }
        
        return factory::response($response);
        
    }
    
    public function post($pages){}
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
