<?php

elgg_register_event_handler('init','system', 'minds_widgets_init');

function minds_widgets_init(){
  
    global $CONFIG;
      
    if (!$CONFIG->minds_widgets)
        $CONFIG->minds_widgets = array(
            'remind',
            'subscribe',
           // 'polls',
            'voting',
            'comments',
            'newsfeed'
        );
    
    // Register service endpoints
    foreach ($CONFIG->minds_widgets as $tab) {
        elgg_register_action('minds_widgets/service/'.$tab, dirname(__FILE__) . '/actions/'.$tab.'.php');
    }
    
    // Extend public pages
    elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'minds_widgets_public_handler'); 
    
    // Lite embed CSS
    $url = elgg_get_simplecache_url('css', 'widgets');
    elgg_register_css('widgets', $url);
	
	$url = elgg_get_simplecache_url('js', 'widgets');
    elgg_register_js('widgets', $url);
    
    // Endpoint
    elgg_register_page_handler('widgets', 'minds_widgets_page_handler');    
    elgg_register_event_handler('pagesetup', 'system', 'minds_widgets_pagesetup');
}

function minds_widgets_page_handler($pages) {


        set_input('tab', $pages[0]);
        if (!$pages[0])
            set_input('tab', 'remind');

        if (isset($pages[1])) {

            switch ($pages[1]) {

                // Load CSS
                case 'css' :
                        echo elgg_view('minds_widgets/css');
                    break;

                // Actually use the service: the service endpoint
                case 'service' :
                        require_once(dirname(__FILE__) . '/pages/service.php') ;
                        return true;
                    break;
		
		// Data (e.g. counters)
		case 'data' :
                        require_once(dirname(__FILE__) . '/pages/data.php') ;
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
}

function minds_widgets_public_hander ($hook, $handler, $return, $params){
        $pages = array('widgets/.*/service/');
        return array_merge($pages, $return);
}

function minds_widgets_pagesetup() {

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
}	


function minds_widgets_remove_url_schema($url) {
    $noschema = substr($url, 0);
    $noschema = str_replace('http:', '', $noschema);
    return str_replace('https:', '', $noschema);
}
