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
use Minds\Api\Factory;

class register implements interfaces\api, interfaces\ApiIgnorePam{

    /**
     * NOT AVAILABLE
     */      
    public function get($pages){
                
        return Factory::response(array('status'=>'error', 'message'=>'GET is not supported for this endpoint'));
        
    }
    
    /**
     * Registers a user
     * @param array $pages
     * 
     * API:: /v1/register
     */
    public function post($pages){
       
        try{
            $guid = register_user($_POST['username'], $_POST['password'], $_POST['username'], $_POST['email'], false);
            $params = array(
                'user' => new entities\user($guid),
                'password' => $_POST['password'],
                'friend_guid' => "",
                'invitecode' => ""
            );
            elgg_trigger_plugin_hook('register', 'user', $params, TRUE);
            $response = array('guid'=>$guid);
        } catch (\Exception $e){
            $response = array('status'=>'error', 'message'=>$e->getMessage());
        }
        return Factory::response($response);
        
    }
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
