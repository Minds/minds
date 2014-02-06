<?php
/**
 * Control | A management system for Minds.
 * 
 * Current features: - bug reporting (export to asana)
 *					 - 
 * Todo: - Nagios integrations
 * 		 - Project management 
 * 		 - Git/Github hooks
 */
 

elgg_register_event_handler('init', 'system', 'control_init');

/**
 * Init for control plugin
 * @return void
 */
function control_init() {
	
	elgg_register_library('control:tickets', dirname(__FILE__).'/lib/tickets.php');
	elgg_register_library('asana', dirname(__FILE__).'/vendors/asana/asana.php');
	
	elgg_register_page_handler('control', 'control_page_handler');
	
	$actions_path = elgg_get_plugins_path() . 'control/actions/control';
	elgg_register_action('control/tickets/add', "$actions_path/tickets/add.php");
	elgg_register_action('control/tickets/edit', "$actions_path/tickets/edit.php");
	elgg_register_action('control/tickets/delete', "$actions_path/tickets/delete.php");
}

/**
 * Control page handler
 * 
 * @param array $page - array of pages
 * @return bool
 */
function control_page_handler($page){
	switch($page[0]){
		case 'tickets':
			elgg_load_library('control:tickets');
			return control_tickets_page_handler($page);
			break;
		default:
			$content = 'Page not found';
	}
	return false;
}
