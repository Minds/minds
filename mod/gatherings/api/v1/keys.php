<?php
/**
 * Minds Newsfeed API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\gatherings\api\v1;

use minds\core;
use minds\plugin\gatherings\entities;
use minds\plugin\gatherings\helpers;
use minds\interfaces;
use minds\api\factory;

class keys implements interfaces\api{

    /**
     * Returns the private key belonging to a user
     * @param array $pages
     * 
     * API:: /v1/keys
     */      
    public function get($pages){
        
        $unlock_password = get_input('password');
        $new_password = get_input('new_password');
        $tmp = helpers\openssl::temporaryPrivateKey(\elgg_get_plugin_user_setting('privatekey', elgg_get_logged_in_user_guid(), 'gatherings'), $unlock_password, NULL);
        $pub = \elgg_get_plugin_user_setting('publickey', elgg_get_logged_in_user_guid(), 'gatherings');
       
	if($tmp){
            $response['key'] = $tmp;
        } else {
            $response['status'] = 'error';
            $response['message'] = "please check your password";
        }
    
        return factory::response($response);
        
    }
    
    public function post($pages){
        
       
        
    }
    
    public function put($pages){
        
        return factory::response(array());
        
    }
    
    public function delete($pages){
        
        return factory::response(array());
        
    }
    
}
        
