<?php

elgg_register_event_handler('init','system',function(){
  
    global $CONFIG;
      
    if (!$CONFIG->minds_widgets)
        $CONFIG->minds_widgets = array(
            'remind',
            'subscribe',
            'polls',
            'voting',
            'comments',
            'newsfeed'
        );
    
    // Register service endpoints
    elgg_register_action('minds_widgets/service/remind', dirname(__FILE__) . '/actions/remind.php');
    
    // Extend public pages
    elgg_register_plugin_hook_handler('public_pages', 'walled_garden', function ($hook, $handler, $return, $params){
	$pages = array('widgets/.*/service/');
	return array_merge($pages, $return);
    });
    
    // Endpoint
    elgg_register_page_handler('widgets', function($pages) {
        
        
        set_input('tab', $pages[0]);
        if (!$pages[0])
            set_input('tab', 'remind');
        
        if (isset($pages[1])) {
            
            switch ($pages[1]) {
                // Actually use the service: the service endpoint
                case 'service' :
                        require_once(dirname(__FILE__) . '/pages/service.php') ;
                        return true;
                    break;
                
                // Get the code
                case 'getcode':
                default:
                    
                    gatekeeper();
                    echo elgg_view('minds_widgets/templates/' . $pages[0], 
                        array(
                            'user' => elgg_get_logged_in_user_entity(),
                            'tab' => $pages[0]
                        )
                    );
                    exit;
            }
        }
        else {
            
            gatekeeper();
            require_once(dirname(__FILE__) . '/pages/widgets.php');
        }
        
        return true;
    });
    
    elgg_register_event_handler('pagesetup', 'system', function() {
        
        global $CONFIG;
        
        // Set up settings
        if(elgg_get_context() == 'settings' ){
		if(elgg_is_logged_in()){
			$params = array(
				'name' => 'widget_settings',
				'text' => elgg_echo('minds_widgets:menu'),
				'href' => "widgets",
			);
			elgg_register_menu_item('page', $params);
		}
	}
        
        // Set up widget menus
        if (elgg_get_context() == 'widgets') {
            
            foreach ($CONFIG->minds_widgets as $tab) {
                $url = "widgets/$tab";
                $item = new ElggMenuItem('minds_widgets:tab:'.$tab, elgg_echo('minds_widgets:tab:'.$tab), $url);
                elgg_register_menu_item('page', $item);
            }
            
        }
        
    });
});	