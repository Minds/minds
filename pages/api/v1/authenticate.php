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

class authenticate implements interfaces\api, interfaces\ApiIgnorePam{

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
     * @SWG\Post(
     *     summary="Create a new channel",
     *     path="/v1/register",
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function post($pages){
        if(!Core\Security\XSRF::validateRequest()){
            return false;
        }

        $user = new entities\user($_POST['username']);
        if($user->isEnabled() && login($user) && Core\session::isLoggedIn())
            $response['status'] = 'success';
        else
            $response['status'] = 'failed';

        return Factory::response($response);
        
    }
    
    public function put($pages){}
    
    public function delete($pages){
        logout();
        
         return Factory::response(array());
    }
    
}
        
