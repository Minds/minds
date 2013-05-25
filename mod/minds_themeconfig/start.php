<?php

elgg_register_event_handler('init','system',function(){
  
    elgg_register_admin_menu_item('configure', 'theme', 'appearance');
    
    elgg_register_action('theme/edit', dirname(__FILE__) . '/actions/edit.php', 'admin');
    
    elgg_register_page_handler('themeicons', function($pages) {
        
        global $CONFIG;
        
        switch($pages[0]) {
            case 'logo_main' :
            case 'logo_topbar' :
                $theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';
    
                $contents = file_get_contents($theme_dir . $pages[0].'.jpg');
                header("Content-type: image/jpeg");
                header('Expires: ' . date('r', strtotime("+6 months")), true);
                header("Pragma: public");
                header("Cache-Control: public");
                header("Content-Length: " . strlen($contents));
                
                $split_string = str_split($contents, 1024);
                foreach ($split_string as $chunk) {
                        echo $chunk;
                }
                exit;
                
                break;
        }
    });
    
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
    });
});	