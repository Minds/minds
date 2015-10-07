<?php
/**
 * Minds Logout Endpoint
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;
use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class logout implements Interfaces\api{

    public function get($pages){}
    
    /**
     * Logout
     * @param $pages
     * 
     * @SWG\Post(
     *     summary="Logout",
     *     path="/v1/logout",
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function post($pages){
error_log("logout request received");        
        $db = new Core\Data\Call('entities');
        $db->removeAttributes(Core\Session::getLoggedinUser()->guid, array('surge_token'));

        //remove the oauth access token
        \minds\plugin\oauth2\storage::remove($_POST['access_token']);
        
    }

    public function put($pages){}
    public function delete($pages){}

}
