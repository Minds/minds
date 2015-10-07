<?php
/**
 * Minds Subscriptions
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class subscribe implements Interfaces\api{

    /**
     * Returns the entities
     * @param array $pages
     * 
     * API:: /v1/subscribe/subscriptions/:guid or /v1/subscribe/subscribers/:guid
     */      
    public function get($pages){
        $response = array();
        
        switch($pages[0]){
            case 'subscriptions':
                $db = new \Minds\Core\Data\Call('friends');
                $subscribers= $db->getRow($pages[1], array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
                $users = array();
                foreach($subscribers as $guid => $subscriber){
                    if($guid == get_input('offset'))
                        continue;
                    if(is_numeric($subscriber)){
                        //this is a local, old style subscription
                        $users[] = new \Minds\Entities\User($guid);
                        continue;
                    } 
                    
                    $users[] = new \Minds\Entities\User(json_decode($subscriber,true));
                }
                $response['users'] = factory::exportable($users);
                $response['load-next'] = (string) end($users)->guid;
                $response['load-previous'] = (string) key($users)->guid;
                break;
            case 'subscribers':
                $db = new \Minds\Core\Data\Call('friendsof');
                $subscribers= $db->getRow($pages[1], array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
                $users = array();
                if(get_input('offset') && key($subscribers) != get_input('offset')){
                    $response['load-previous'] = (string) get_input('offset');
                } else {
                    foreach($subscribers as $guid => $subscriber){
                        if($guid == get_input('offset')){
                            unset($subscribers[$guid]);
                            continue;
                        }
                        if(is_numeric($subscriber)){
                            //this is a local, old style subscription
                            $users[] = new \Minds\Entities\User($guid);
                            continue;
                        } 
                        
                        $users[] = new \Minds\Entities\User(json_decode($subscriber,true));
                    }
            
                    $response['users'] = factory::exportable($users);
                    $response['load-next'] = (string) end($users)->guid;
                    $response['load-previous'] = (string) key($users)->guid;
                }
                break;
        }
        
        return Factory::response($response);
        
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
         \Minds\plugin\payments\start::createTransaction(Core\Session::getLoggedinUser()->guid, 1, $pages[0], 'subscribed');
        if(!$success){
            $response = array(
                'status' => 'error'
            );
        }
        
        return Factory::response($response);
        
    }
    
    public function put($pages){}
    
    public function delete($pages){
       $success = elgg_get_logged_in_user_entity()->unSubscribe($pages[0]);
        $response = array('status'=>'success');
         \Minds\plugin\payments\start::createTransaction(Core\Session::getLoggedinUser()->guid, -1, $pages[0], 'unsubscribed');
        if(!$success){
            $response = array(
                'status' => 'error'
            );
        }

        return Factory::response($response); 
    }
    
}
        
