<?php
	 
    function custom_index_widgets_init() {
	

		elgg_extend_view('css','custom_index_widgets/css');
		elgg_extend_view('page/elements/head','custom_index_widgets/js');
		
		
		/***********************************************************************************************/
		/* Please, il you like the custom Index Widget Pluggin, let me add a small link in your footer */
		elgg_extend_view('page/elements/footer','custom_index_widgets/footerlinks');
		
		/***********************************************************************************************/
		
		elgg_register_admin_menu_item('configure', 'custom_index_widgets', 'appearance');
		
		
		$ciw_layout = elgg_get_plugin_setting("ciw_layout", "custom_index_widgets");
		if ($ciw_layout == NULL)
			 set_plugin_setting("ciw_layout", "index_2rmsb", "custom_index_widgets");
			 
		$ciw_showdashboard = elgg_get_plugin_setting("ciw_showdashboard", "custom_index_widgets");
		if ($ciw_showdashboard == NULL)
			 set_plugin_setting("ciw_showdashboard", "yes", "custom_index_widgets");
    			 
		elgg_register_widget_type('latest_members_index',elgg_echo ('custom_index_widgets:latest_members_index'),elgg_echo ('custom_index_widgets:latest_members_index'), "custom_index_widgets", true);
		elgg_register_widget_type('inline_content_index',elgg_echo ('custom_index_widgets:inline_content_index'),elgg_echo ('custom_index_widgets:inline_content_index'), "custom_index_widgets", true);
		elgg_register_widget_type('rich_media_index',elgg_echo ('custom_index_widgets:rich_media_index'),elgg_echo ('custom_index_widgets:rich_media_index'), "custom_index_widgets", true);
		elgg_register_widget_type('latest_generic_index',elgg_echo ('custom_index_widgets:latest_generic_index'),elgg_echo ('custom_index_widgets:latest_generic_index'), "custom_index_widgets", true);
		elgg_register_widget_type('latest_activity_index',elgg_echo ('custom_index_widgets:latest_activity_index'),elgg_echo ('custom_index_widgets:latest_activity_index'), "custom_index_widgets", true);
		elgg_register_widget_type('cloud_generic_index',elgg_echo ('custom_index_widgets:cloud_generic_index'),elgg_echo ('custom_index_widgets:cloud_generic_index'), "custom_index_widgets", true);
		elgg_register_widget_type('social_share_index',elgg_echo ('custom_index_widgets:social_share_index'),elgg_echo ('custom_index_widgets:social_share_index'), "custom_index_widgets", true);
		elgg_register_widget_type('login_index',elgg_echo ('custom_index_widgets:login_index'),elgg_echo ('custom_index_widgets:login_index'), "custom_index_widgets", true);

        if(elgg_is_active_plugin('groups'))	
          elgg_register_widget_type('latest_groups_index',elgg_echo ('custom_index_widgets:latest_groups_index'),elgg_echo ('custom_index_widgets:latest_groups_index'), "custom_index_widgets", true);
         
        if(elgg_is_active_plugin('file'))   	
          elgg_register_widget_type('latest_files_index',elgg_echo ('custom_index_widgets:latest_files_index'),elgg_echo ('custom_index_widgets:latest_files_index'), "custom_index_widgets", true);
        
        if(elgg_is_active_plugin('news'))
          elgg_register_widget_type('latest_news_index',elgg_echo ('custom_index_widgets:latest_news_index'),elgg_echo ('custom_index_widgets:latest_news_index'), "custom_index_widgets",true);
        
        if(elgg_is_active_plugin('bookmarks_enhanced') or elgg_is_active_plugin('bookmarks'))
          elgg_register_widget_type('latest_bookmarks_index',elgg_echo ('custom_index_widgets:latest_bookmarks_index'),elgg_echo ('custom_index_widgets:latest_bookmarks_index'), "custom_index_widgets",true);
        
        if(elgg_is_active_plugin('blog'))
          elgg_register_widget_type('latest_blogs_index',elgg_echo ('custom_index_widgets:latest_blogs_index'),elgg_echo ('custom_index_widgets:latest_blogs_index'), "custom_index_widgets",true);
        
        if(elgg_is_active_plugin('pages'))
          elgg_register_widget_type('latest_pages_index',elgg_echo ('custom_index_widgets:latest_pages_index'),elgg_echo ('custom_index_widgets:latest_pages_index'), "custom_index_widgets",true);
        
	    if(elgg_is_active_plugin('event_calendar'))
          elgg_register_widget_type('latest_events_index',elgg_echo ('custom_index_widgets:latest_events_index'),elgg_echo ('custom_index_widgets:latest_events_index'), "custom_index_widgets",true);

      	if(elgg_is_active_plugin('tidypics')){ 
		  elgg_register_widget_type('latest_photos_index', elgg_echo("tidypics:widget:latest"), elgg_echo("tidypics:widget:latest_descr"), "custom_index_widgets", true);
		  elgg_register_widget_type('latest_album_index', elgg_echo("tidypics:widget:albums"), elgg_echo("tidypics:widget:latest_descr"), "custom_index_widgets",true);
		}
		if(elgg_is_active_plugin('thewire'))
          elgg_register_widget_type('latest_wire_index',elgg_echo ('custom_index_widgets:latest_wire_index'),elgg_echo ('custom_index_widgets:latest_wire_index'), "custom_index_widgets",true);
		
		if(elgg_is_active_plugin('tasks'))
          elgg_register_widget_type('latest_tasks_index',elgg_echo ('custom_index_widgets:latest_tasks_index'),elgg_echo ('custom_index_widgets:latest_tasks_index'), "custom_index_widgets",true);
		  
		if(elgg_is_active_plugin('izap_videos')) 
          elgg_register_widget_type('latest_izap_videos_index',elgg_echo  ('custom_index_widgets:latest_izap_videos_index'),elgg_echo ('custom_index_widgets:latest_izap_videos_index'), "custom_index_widgets", true);
		
		if(elgg_is_active_plugin('simplepie')) 
			elgg_register_widget_type('feed_reader_index', elgg_echo('simplepie:widget'),elgg_echo('simplepie:description'),'custom_index_widgets',	true);
		
		elgg_register_plugin_hook_handler('index','system','custom_index_widgets');
    }
    
    function custom_index_widgets($hook, $type, $return, $params) {
		if ($return == true) {
			// another hook has already replaced the front page
			return $return;
		}

		if (!include_once(dirname(__FILE__) . "/index.php")) {
			return false;
		}

		// return true to signify that we have handled the front page
		return true;
	}


	function custom_index_widgets_page_handler($page) {
		global $CONFIG;

		if (isset ( $page [0] )) {
			
			switch ($page [0]) {
				case "edit" :
					@include (dirname ( __FILE__ ) . "/edit.php");
					break;
			}
		} else {
			register_error ( elgg_echo ( "custom_index_widgets:admin:notfound" ) );
			forward ( $CONFIG->wwwroot );
		}
		return true;
	}
	
	
	function custom_index_show_widget_area($areawidgets){
		if (is_array($areawidgets) && sizeof($areawidgets) > 0)
			foreach($areawidgets as $widget) {
			 if ($widget instanceof ElggWidget){
					$vars['entity'] = $widget;
					$handler = $widget->handler;
					if (elgg_view_exists("widgets/$handler/content")) {
						$content = elgg_view("widgets/$handler/content", $vars);
					} else {
						elgg_deprecated_notice("widgets use content as the display view", 1.8);
						$content = elgg_view("widgets/$handler/view", $vars);
					}
					echo elgg_view_module('featured',  $widget->title, $content, array('class' => 'elgg-module-highlight'));
				}
				else
					echo $widget;
		}
	}
	
	
	function custom_index_build_columns($area_widget_list, $widgettypes, $build_server_side=TRUE){

		$column_widgets_view = array();
    	$column_widgets_string="";
		
		if (is_array($area_widget_list) && sizeof($area_widget_list) > 0) {
	  		foreach($area_widget_list as $widget) {
					if($build_server_side ){
						$title = $widget->widget_title;
						if (!$title)
							$title = $widgettypes[$widget->handler]->name;
						if (!$title)
							$title = $widget->handler;
						$widget->title = $title;
						
						if (($widget->guest_only == "yes" && !elgg_is_logged_in()) || $widget->guest_only == "no" || !isset($widget->guest_only))
							$column_widgets_view[] = $widget;  
						
					} else {
						
						if (!empty($column_widgets_string)) {
							$column_widgets_string .= "::";
						}
						$column_widgets_string .= "{$widget->handler}::{$widget->getGUID()}";
						
					}
	  		}
			
			if($build_server_side)
				return $column_widgets_view;
			else
				return $column_widgets_string;
		}
		return NULL;	
	}
  
  elgg_register_event_handler('init','system','custom_index_widgets_init');
  elgg_register_page_handler ( 'custom_index_widgets', 'custom_index_widgets_page_handler'); 
  elgg_register_action('custom_index_widgets/reset',false,$CONFIG->pluginspath . 'custom_index_widgets/actions/reset.php',true);

?>
