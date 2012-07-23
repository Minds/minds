<?php
	/**
	 * @file start.php
	 * @brief Set the views_counter plugin on elgg system
	 */

    include('lib/views_counter.php');

	/**
	 * Set the views_counter basic configuration on elgg system
	 */
	function views_counter_init() {
		// Try to add a views counter for the entities selected through the plugin settings
		set_views_counter();
		
		elgg_register_page_handler('views_counter','views_counter_page_handler');
		elgg_extend_view('css/elgg','views_counter/css');
	}
	
	
	/**
	 * Set some views_counter links on elgg system
	 */
	function views_counter_pagesetup() {
		if (elgg_is_admin_logged_in() && (elgg_get_context()=='admin') || (elgg_get_context() == 'views_counter')) {
		  $item = new ElggMenuItem('views_counter_admin', elgg_echo('views_counter:admin_page'), elgg_get_site_url() . 'views_counter/list_entities/user');
	      elgg_register_menu_item('page', $item);
		}
	}
	
	/**
	 * To control the views_counter pages exhibition
	 * 
	 * @param $page
	 */
	function views_counter_page_handler($page) {
		if (isset($page[0])) {
			
		  $return = FALSE;
			switch($page[0]) {
				case 'list_entities':
					set_input('entity_type',$page[1]);
					if(include(elgg_get_plugins_path() . 'views_counter/admin_page.php')){
					  $return = TRUE;
					}
					break;

				case 'views_statistics':
					set_input('entity_guid',$page[1]);
					if(include(elgg_get_plugins_path() . 'views_counter/views_statistics.php')){
					  $return = TRUE;
					}
					break;
			}
		}
		
		return $return;
	}
	
	/**
	 * To indicate that a view counting system exists
	 * 
	 * @param $hook
	 * @param $type
	 * @param $returnvalue
	 * @param $params
	 */
	function views_counter_register($hook, $type, $returnvalue, $params) {
		return 'views_counter';
	}
	
	/**
	 * To register a function that get the hooks from another plugins for list entities by number of views
	 * 
	 * @param $hook
	 * @param $type
	 * @param $returnvalue
	 * @param $params
	 */
	function list_entities_by_views_counter_hook($hook, $type, $returnvalue, $params) {
		$options = $params;
		return list_entities_by_views_counter($options);
	}
	
	/**
	 * To register a function that get the hooks from another plugins for get entities by number of views
	 * 
	 * @param $hook
	 * @param $type
	 * @param $returnvalue
	 * @param $params
	 */
	function get_entities_by_views_counter_hook($hook, $type, $returnvalue, $params) {
		$options = $params;

		return get_entities_by_views_counter($options);
	}
	
	/**
	 * A hook that may be used by other plugins that want to get the number of views for an entity
	 * 
	 * @param $hook
	 * @param $type
	 * @param $returnvalue
	 * @param $params
	 */
	function get_views_counter_hook($hook, $type, $returnvalue, $params) {
		if ($params['entity']) {
			// We get the entity as a hook params instead of the entity_guid just for follow the elgg pattern of pass the entity instead of the entity_guid
			return get_views_counter($params['entity']->guid, $params['owner_guid']);
		}
	}

	/**
	 * Hook that allow other plugins to get the last view time for an entity
	 * 
	 * @param $hook
	 * @param $type
	 * @param $returnvalue
	 * @param $params
	 */
	function get_last_view_time_hook($hook, $type, $returnvalue, $params) {
		if($params['entity']) {
			// We get the entity as a hook params instead of the entity_guid just for follow the elgg pattern of pass the entity instead of the entity_guid
			$user_guid = (isset($params['user_guid'])) ? ($params['user_guid']) : 0;
			return get_last_view_time($params['entity']->guid, $user_guid);
		}
	}
	
	/**
	 * Hook that allow other plugins to update the last view time for an entity
	 * 
	 * @param $hook
	 * @param $type
	 * @param $returnvalue
	 * @param $params
	 */
	function update_last_view_time_hook($hook, $type, $returnvalue, $params) {
		if($params['entity']) {
			$user_guid = ($params['user']) ? ($params['user']->guid) : 0;
			return update_last_view_time($params['entity']->guid, $user_guid);
		}
	}
	
	/**
	 * Hook that allow other plugins to check if a user had seen the last update of an entity
	 * 
	 * @param $hook
	 * @param $type
	 * @param $returnvalue
	 * @param $params
	 */
	function did_see_last_update_hook($hook, $type, $returnvalue, $params) {
		if($params['entity']) {
			$user_guid = ($params['user']) ? ($params['user']->guid) : 0;
			return did_see_last_update($params['entity']->guid, $user_guid);
		}
	}
	
	elgg_register_event_handler('init','system','views_counter_init');
	elgg_register_event_handler('pagesetup','system','views_counter_pagesetup');
	
	// To manage the settings inputs are more than one value for input (checkboxes)
	elgg_register_plugin_hook_handler('plugin:setting','plugin','views_counter_settings_handler');
	
	// Get the hook 'view_counting_system' from other plugins that may be asking if some view counting system exists
	elgg_register_plugin_hook_handler('views_counting_system','plugin','views_counter_register');
	
	// A hook that may be used by other plugins that want to list the entities by number of views but without get dependent of any specific plugin
	// Obs.: Maybe the job of trigger a hook is on account of elgg_list_entities() function  
	elgg_register_plugin_hook_handler('list_entities_by_views_counter_hook','plugin','list_entities_by_views_counter_hook');
	
	// A hook that may be used by other plugins that want to get the entities by the number of views without get depedent of any specific plugin
	elgg_register_plugin_hook_handler('get_entities_by_views_counter_hook','plugin','get_entities_by_views_counter_hook');
	
	// A hook that may be used by other plugins that want to get the number of views for an entity
	elgg_register_plugin_hook_handler('get_views_counter_hook','plugin','get_views_counter_hook');
	
	// A hook that may be used by other plugins that want to get the last view time for an entity
	elgg_register_plugin_hook_handler('get_last_view_time_hook','plugin','get_last_view_time_hook');
	
	// A hook that may be used by other plugin that want to update the last view time of an entity
	elgg_register_plugin_hook_handler('update_last_view_time_hook','plugin','update_last_view_time_hook');
	
	// A hook thay may be used by other plugins that want to check if an user had seen the last update of an elgg entity
	elgg_register_plugin_hook_handler('did_see_last_update_hook','plugin','did_see_last_update_hook');
	
	
	elgg_register_action('views_counter/settings/save', elgg_get_plugins_path() . 'views_counter/actions/views_counter/settings/save.php', 'admin');
	elgg_register_action('views_counter/create_demo_entity', elgg_get_plugins_path() . 'views_counter/actions/create_demo_entity.php', 'admin');
	elgg_register_action('entities/delete', elgg_get_plugins_path() . 'views_counter/actions/delete_entity.php');
	elgg_register_action('views_counter/list_entities', elgg_get_plugins_path() . 'views_counter/actions/list_entities.php');
