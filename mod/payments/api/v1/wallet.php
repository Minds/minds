<?php
/**
 * Minds Wallet API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\payments\api\v1;

use Minds\Core;
use minds\interfaces;
use minds\api\factory;

class wallet implements interfaces\api{

    /**
     * Returns the wallet info
     * @param array $pages
     * 
     * API:: /v1/wallet/:slug
     */      
    public function get($pages){
        
        $response = array();
        
        switch($pages[0]){
            
            case "count":
                $response['count'] = 5;
                break;
                
            case "transactions":
                
                break;
                
        }
    
        return factory::response($response);
        
    }
    
    public function post($pages){
        

        return factory::response($response);
    }
    
    public function put($pages){
        
        return factory::response(array());
        
    }
    
    public function delete($pages){
        
        return factory::response(array());
        
    }
    
}
        
