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
use Minds\Api\Factory;
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
            return Factory::response(array('status'=>'error', 'message'=>'The user could not be found'));
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
        
        

        return Factory::response($response);
        
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
            case "banner":
                $item = new \minds\entities\carousel();
                $item->title = '';
                $item->owner_guid = elgg_get_logged_in_user_guid();
                $item->access_id = ACCESS_PUBLIC;
                $item->save();
                $files = array();
                $sizes = array(
                    'thin' => array(
                        'w' => 2000,
                        'h' => 800,
                        'square' => false,
                        'upscale' => true
                        ),
                    'fat' => array(
                        'w' => 2000,
                        // 'h' => 800,
                        'square' => false,
                        'upscale' => true
                        )
                    );
                foreach ($sizes as $name => $size_info) {
                    global $CONFIG;
                    $theme_dir = $CONFIG->dataroot . 'carousel/';
                    $dimensions = getimagesize($_FILES["file"]['tmp_name']);
                    $h = $dimensions[1];
                    $x1 = 0;
                    $x2 = $dimensions[0];
                    if($h <= 800 || $size_info['h'] != 800){
                        $y1 = 0;
                        $y2 = $h;
                    } else {
                        $y1 = $h/3;
                        $y2 = $h-($h/3);
                    }
                    $resized = get_resized_image_from_existing_file($_FILES["file"]['tmp_name'], $size_info['w'], $size_info['h'], $size_info['square'], $x1, $y1, $x2, $y2, $size_info['upscale'], 'jpeg', 80);
                    if ($resized) {
                        @mkdir($theme_dir);
                        file_put_contents($theme_dir . $item->guid . $name, $resized);
                    }
                    if (isset($_FILES["file"]) && ($_FILES["file"]['error'] != UPLOAD_ERR_NO_FILE) && $_FILES["file"]['error'] != 0) {
                    // register_error(minds_themeconfig_codeToMessage($_FILES['logo']['error'])); // Debug uploads
                    }
                    $item->last_updated = time();
                    $item->background = true;
                }
                $item->save();    
            break;
            case "info":
            default:
                if(!$owner->canEdit()){
                    return Factory::response(array('status'=>'error'));
                }
                foreach(array('name', 'website') as $field){
                    if(isset($_POST[$field]))
                        $owner->$field = $_POST[$field];
                }
                $owner->save();
       }
        
       return Factory::response(array());
        
    }
    
    public function put($pages){
        
        return Factory::response(array());
        
    }
    
    public function delete($pages){
        
        return Factory::response(array());
        
    }
    
}
        
