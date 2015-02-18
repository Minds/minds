<?php
/**
 * Minds Boost Api endpoint
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
use Minds\Core\Boost;
use minds\entities;
use minds\interfaces;
use minds\api\factory;

class boost implements interfaces\api{

    /**
     * Not implemented
     */      
    public function get($pages){
        $response = array();
        return factory::response($response);
    }
    
    /**
     * Boost an entity
     * @param array $pages
     * 
     * API:: /v1/boost/:type/:guid
     */
    public function post($pages){
        
        if(!isset($pages[0]))
             return factory::response(array('status' => 'error', 'message' => ':type must be passed in uri'));
        
        if(!isset($pages[1]))
            return factory::response(array('status' => 'error', 'message' => ':guid must be passed in uri'));
        
        if(!isset($_POST['impressions']))
            return factory::response(array('status' => 'error', 'message' => 'impressions must be sent in post body'));
        
        $response = array();
	    if(!Boost\Factory::build(ucfirst($pages[0]))->boost($pages[1], $_POST['impressions']))
	        $response['status'] = 'error';
        
        return factory::response($response);
        
    }
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
