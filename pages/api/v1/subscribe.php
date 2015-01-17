<?php
/**
 * Minds Subscriptions
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
use minds\entities;
use minds\interfaces;
use minds\api\factory;

class subscribe implements interfaces\api{

    /**
     * Returns the entities
     * @param array $pages
     * 
     * API:: /v1/entities/ or /v1/entities/all
     */      
    public function get($pages){
        
        $response = array(
            'status' => 'error',
            'message' => 'not implemented yet'
            );
        return factory::response($response);
        
    }
    
    /**
     * Subscribes a user to another
     * @param array $pages
     * 
     * API:: /v1/subscriptions/:guid
     */
    public function post($pages){
        
	$success = elgg_get_logged_in_user_entity()->subscribe($pages[0]);
        $response = array('status'=>'success');
        
        if(!$success){
            $response = array(
                'status' => 'error'
            );
        }
        
        return factory::response($response);
        
    }
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
