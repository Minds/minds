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
use minds\interfaces;
use minds\api\factory;

class conversations implements interfaces\api{

    /**
     * Returns the conversations or conversation
     * @param array $pages
     * 
     * API:: /v1/conversations
     */      
    public function get($pages){
        
        $response = array();
        
        if(isset($pages[0])){
            
            $conversation = new entities\conversation(elgg_get_logged_in_user_guid(), $pages[0]);

            $ik = $conversation->getIndexKeys();
            $guids = core\data\indexes::fetch("object:gathering:conversation:".$ik[0], array('limit'=>get_input('limit',12), 'offset'=>get_input('offset')));

            if($guids){
            
                $messages = core\entities::get(array('guids'=>$guids));
                
                if($messages){
                    
                    foreach($messages as $k => $message){
                        $key = "message:".elgg_get_logged_in_user_guid();
                        $messages[$k]->message = base64_decode($messages[$k]->$key);
                    }
                    
                    $messages = array_reverse($messages);
                    $response['messages'] = factory::exportable($messages);
                    $response['load-next'] = (string) end($messages)->guid;
                    $response['load-previous'] = (string) reset($messages)->guid;
                }
            }
            
            //return the public keys
            $response['publickeys'] = array(
                elgg_get_logged_in_user_guid() => elgg_get_plugin_user_setting('publickey', elgg_get_logged_in_user_guid(), 'gatherings'),
                $pages[0] => elgg_get_plugin_user_setting('publickey', $pages[0], 'gatherings')
            );
            
        } else {
        
            $conversations = \minds\plugin\gatherings\start::getConversationsList();
    
            if($conversations){
                $response['conversations'] = factory::exportable($conversations);
            }
            
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
        