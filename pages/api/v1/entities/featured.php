<?php
/**
 * Minds Featured API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1\entities;

use Minds\Core;
use minds\entities;
use minds\interfaces;
use Minds\Api\Factory;

class featured implements interfaces\api{

    /**
     * Returns the entities
     * @param array $pages
     * 
     * API:: /v1/entities/ or /v1/entities/all
     */      
    public function get($pages){

        if(isset($pages[1]) && $pages[1] == 'video')
            $pages[1] = 'video';
        
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

	    $key = $options['type'] . ':featured';
    	if($options['subtype'])
    		$key = $options['type'] . ':' . $options['subtype'] . ':featured';

    	$guids = core\Data\indexes::fetch($key, $options);
    	if(!$guids){
	    	return Factory::response(array('status'=>'error', 'message'=>'not found'));
    	}
        
        $options = array('guids'=>$guids);
        $entities = core\entities::get($options);
 	

        if($entities){
            $response['entities'] = factory::exportable($entities);
            $response['load-next'] = (string) end($entities)->guid;
            $response['load-previous'] = (string) key($entities)->guid;
        }
        
        return Factory::response($response);
        
    }
    
    public function post($pages){}
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
