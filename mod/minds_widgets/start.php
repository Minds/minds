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
        
        set_input('widget', $pages[0]);
        
        require_once(dirname(__FILE__) . '/pages/widgets.php');
        
        return true;
    });
    
    elgg_register_event_handler('pagesetup', 'system', function() {
        
        global $CONFIG;
        
        // Set up menus
        if (elgg_get_context() == 'widgets') {
            
            foreach ($CONFIG->minds_widgets as $tab) {
                $url = "widgets/$tab";
                $item = new ElggMenuItem('minds_widgets:tab:'.$tab, elgg_echo('minds_widgets:tab:'.$tab), $url);
                elgg_register_menu_item('page', $item);
            }
            
        }
        
    });
});	