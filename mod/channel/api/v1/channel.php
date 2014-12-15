<?php
/**
 * Minds Channel API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\channel\api\v1;

use minds\core;
use minds\interfaces;
use minds\entities;
use minds\api\factory;

class channel implements interfaces\api{

    /**
     * Return channel profile information
     * @param array $pages
     * 
     * API:: /v1/channel/:username
     */      
    public function get($pages){
        
        if($pages[0] == 'me')
            $pages[0] = elgg_get_logged_in_user_guid();
        
        $user = new entities\user($pages[0]);
        if(!$user->username){
            return factory::response(array('status'=>'error', 'message'=>'The user could not be found'));
        }
        
        $return = factory::exportable(array($user));
        
        $response['channel'] = $return[0];
        $response['channel']['avatar_url'] = array(
            'tiny' => $user->getIconURL('tiny'),
            'small' => $user->getIconURL('small'),
            'medium' => $user->getIconURL('medium'),
            'large' => $user->getIconURL('large'),
            'master' => $user->getIconURL('master')
        );
        
        $carousels = core\entities::get(array('subtype'=>'carousel', 'owner_guid'=>$user->guid));
        foreach($carousels as $carousel){
            global $CONFIG;
            if(!$CONFIG->cdn_url)
                $CONFIG->cdn_url = elgg_get_site_url();
            else 
                $CONFIG->cdn_url .= '/';
            
           $response['channel']['carousels'][] = array(
                'src'=> $carousel->ext_bg ?: $bg =  $CONFIG->cdn_url . "carousel/background/$carousel->guid/$carousel->last_updated/$CONFIG->lastcache/fat"
            );
             $response['channel']['carousels'][] = array(
                'src'=> $carousel->ext_bg ?: $bg =  $CONFIG->cdn_url . "carousel/background/$carousel->guid/$carousel->last_updated/$CONFIG->lastcache/fat"
            );
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
        