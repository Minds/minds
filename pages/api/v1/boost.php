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
use Minds\Api\Factory;

class boost implements interfaces\api{

    /**
     * Not implemented
     */      
    public function get($pages){
        $response = array();
        return Factory::response($response);
    }
    
    /**
     * Boost an entity
     * @param array $pages
     * 
     * API:: /v1/boost/:type/:guid
     */
    public function post($pages){
        
        if(!isset($pages[0]))
             return Factory::response(array('status' => 'error', 'message' => ':type must be passed in uri'));
        
        if(!isset($pages[1]))
            return Factory::response(array('status' => 'error', 'message' => ':guid must be passed in uri'));
        
        if(!isset($_POST['impressions']))
            return Factory::response(array('status' => 'error', 'message' => 'impressions must be sent in post body'));
        
        $response = array();
	    if(Core\Boost\Factory::build(ucfirst($pages[0]), array('destination'=>isset($_POST['destination']) ? $_POST['destination'] : NULL))->boost($pages[1], $_POST['impressions'])){
            $points = 0 - $_POST['impressions']; //make it negative
            \Minds\plugin\payments\start::createTransaction(Core\session::getLoggedinUser()->guid, $points, $pages[1], "boost");
            //a boost gift
            if(isset($pages[2]) && $pages[2] != Core\session::getLoggedinUser()->guid){
                Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
                'to'=>array($pages[2]),
                'object_guid' => $pages[1],
                'notification_view' => 'boost_gift',
                'params' => array('impressions'=>$_POST['impressions']),
                'impressions' => $_POST['impressions']
                ));
            } else {
                Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
                'to'=>array(Core\session::getLoggedinUser()->guid),
                'object_guid' => $pages[1],
                'notification_view' => 'boost_submitted',
                'params' => array('impressions'=>$_POST['impressions']),
                'impressions' => $_POST['impressions']
                ));
            }           
        } else {
	        $response['status'] = 'error';
        }

        return Factory::response($response);
        
    }
    
    public function put($pages){}
    
    public function delete($pages){}
    
}
        
