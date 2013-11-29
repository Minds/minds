<?php
elgg_register_event_handler('init','system', 'themeconfig_init');

function themeconfig_init(){
  
    elgg_register_admin_menu_item('configure', 'theme', 'appearance');
    
    elgg_register_action('theme/edit', dirname(__FILE__) . '/actions/edit.php', 'admin');
    
    elgg_register_page_handler('themeicons', 'themeicons_page_handler');
    
    elgg_register_event_handler('pagesetup', 'system', function() {
        
        // Override topbar
        if (elgg_get_plugin_setting('logo_override', 'minds_themeconfig')) {
            if(elgg_get_context()!='main')	{

                    elgg_unregister_menu_item('topbar', 'minds_logo');
                    elgg_register_menu_item('topbar', array(
                            'name' => 'minds_logo',
                            'href' => elgg_get_site_url(),
                            'text' => '<img src=\''. elgg_get_site_url() . 'themeicons/logo_topbar\' class=\'minds_logo\'>',
                            'priority' => 0
                    ));
            }
        }
        
        // Extend the css
        elgg_extend_view('page/elements/head', 'minds_themeconfig/css');
    }, 999);
}

function themeicons_page_handler($pages) {

        global $CONFIG;

        switch($pages[0]) {
            case 'background':
                 $theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';

                $contents = file_get_contents($theme_dir . 'background');
                header("Content-type: " . elgg_get_plugin_setting('background_override_mime', 'minds_themeconfig'));
                header('Expires: ' . date('r', strtotime("+6 months")), true);
                header("Pragma: public");
                header("Cache-Control: public");
                header("Content-Length: " . strlen($contents));

                break;
            case 'logo_main' :
            case 'logo_topbar' :
            case 'logo_favicon' :
                $theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';
               
                $contents = file_get_contents($theme_dir . $pages[0].'.jpg');
                header_remove();
                header("Content-Type: image/jpeg");
               header('Expires: ' . date('r', strtotime("+6 months")), true);
               header("Pragma: public");
               header("Cache-Control: public");
               header("Content-Length: " . strlen($contents));
                break;
        }
        
        if ($contents) {
            
                $split_string = str_split($contents, 1024);
                foreach ($split_string as $chunk) {
                        echo $chunk;
                }
                exit;
        }
    }	

 function minds_themeconfig_codeToMessage($code) 
    { 
        switch ($code) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; 
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message = "The uploaded file was only partially uploaded"; 
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message = "No file was uploaded"; 
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message = "Missing a temporary folder"; 
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message = "Failed to write file to disk"; 
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message = "File upload stopped by extension"; 
                break; 

            default: 
                $message = "Unknown upload error"; 
                break; 
        } 
        return $message; 
    } 
