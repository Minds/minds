<?php
/**
 * Minds Newsfeed API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use minds\core;
use minds\entities;
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
            'offset'=> get_input('offset', '')
        ), $options));
        
        if($activity){
            $response['activity'] = factory::exportable($activity);
            $response['load-next'] = (string) end($activity)->guid;
            $response['load-previous'] = (string) key($activity)->guid;
        }
        
        return factory::response($response);
        
    }
    
    public function post($pages){
        
        //factory::authorize();
        error_log(print_r($_POST, true));
        $activity = new entities\activity();
        if(isset($_POST['message']))
            $activity->setMessage($_POST['message']);
        
        if($guid = $activity->save()){
            return factory::response(array('guid'=>$guid));
        } else {
            return factory::response(array('status'=>'failed', 'message'=>'could not save'));
        }
        
    }
    
    public function put($pages){
        
        return factory::response(array());
        
    }
    
    public function delete($pages){
        
        return factory::response(array());
        
    }
    
}
        