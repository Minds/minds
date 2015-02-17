<?php
/**
 * Minds Channel API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\channel\api\v1;

use Minds\Core;
use minds\interfaces;
use minds\entities;
use minds\api\factory;
use ElggFile;

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
        $response['channel']['chat'] = (bool) elgg_get_plugin_user_setting('option', elgg_get_logged_in_user_guid(), 'gatherings') == 1 ? true : false;


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
        }
        
        

        return factory::response($response);
        
    }
    
    public function post($pages){
        
        $owner = Core\session::getLoggedinUser();
        $guid = Core\session::getLoggedinUser()->guid;
        if(Core\session::getLoggedinUser()->legacy_guid)
            $guid = Core\session::getLoggedinUser()->legacy_guid;
        
        switch($pages[0]){
            case "avatar":
                $icon_sizes = elgg_get_config('icon_sizes');
                
                // get the images and save their file handlers into an array
                // so we can do clean up if one fails.
                $files = array();
                foreach ($icon_sizes as $name => $size_info) {
                    $resized = get_resized_image_from_uploaded_file('file', $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale']);
                
                    if ($resized) {
                        //@todo Make these actual entities.  See exts #348.
                        $file = new ElggFile();
                        $file->owner_guid = Core\session::getLoggedinUser()->guid;
                        $file->setFilename("profile/{$guid}{$name}.jpg");
                        $file->open('write');
                        $file->write($resized);
                        $file->close();
                        $files[] = $file;
                    } else {
                        // cleanup on fail
                        foreach ($files as $file) {
                            $file->delete();
                        }
                
                        register_error(elgg_echo('avatar:resize:fail'));
                        forward(REFERER);
                    }
                }
                
                // reset crop coordinates
                $owner->x1 = 0;
                $owner->x2 = 0;
                $owner->y1 = 0;
                $owner->y2 = 0;
                
                $owner->icontime = time();
                $owner->save();
                break;
            case "info":
            default:
                if(!$owner->canEdit()){
                    return factory::response(array('status'=>'error'));
                }
                foreach(array('name', 'website') as $field){
                    if(isset($_POST[$field]))
                        $owner->$field = $_POST[$field];
                }
                $owner->save();
       }
        
       return factory::response(array());
        
    }
    
    public function put($pages){
        
        return factory::response(array());
        
    }
    
    public function delete($pages){
        
        return factory::response(array());
        
    }
    
}
        
