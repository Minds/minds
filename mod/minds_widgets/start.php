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
    
    elgg_register_page_handler('widgets', function($pages) {
        
        gatekeeper();
        
        set_input('tab', $pages[0]);
        
        if (isset($pages[1])) {
            
            switch ($pages[1]) {
                case 'getcode':
                default:
                    echo htmlentities(elgg_view('minds_widgets/templates/' . $pages[0], 
                        array(
                            'user' => elgg_get_logged_in_user_entity(),
                            'tab' => $pages[0]
                        )
                    ), ENT_NOQUOTES, "UTF-8");
            }
        }
        else
            require_once(dirname(__FILE__) . '/pages/widgets.php');
        
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