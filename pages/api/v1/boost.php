<?php
/**
 * Minds Boost Api endpoint
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
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
	    if(Core\Boost\Factory::build(ucfirst($pages[0]))->boost($pages[1], $_POST['impressions'])){
            $points = 0 - $_POST['impressions']; //make it negative
            \Minds\plugin\payments\start::createTransaction(Core\session::getLoggedinUser()->guid, $points, NULL, "boost");
        } else {
	        $response['status'] = 'error';
        }

        return factory::response($response);
        
    }
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
