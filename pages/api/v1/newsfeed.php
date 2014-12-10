<?php
/**
 * Minds Newsfeed API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use minds\core;
use minds\interfaces;
use minds\api\factory;

class newsfeed implements interfaces\api{

    /**
     * Returns the newsfeed
     * @param array $pages
     * 
     * API:: /v1/newsfeed/
     */      
    public function get($pages){
        
        $response = array();
        
        if(!isset($pages[0]))
            $pages[0] = 'network';
        
        switch($pages[0]){
            default:
            case 'network':
                $options = array(
                    'network' => isset($pages[1]) ? $pages[1] : core\session::getLoggedInUserGuid()
                );
                break;
        }
        

        $activity = core\entities::get(array_merge(array(
            'type' => 'activity',
            'limit' => get_input('limit', 5),
        ), $options));
        
        if($activity)
            $response['activity'] = factory::exportable($activity);
        
        return factory::response($response);
        
    }
    
    public function post($pages){
        
        return factory::response(array());
        
    }
    
    public function put($pages){
        
        return factory::response(array());
        
    }
    
    public function delete($pages){
        
        return factory::response(array());
        
    }
    
}
        