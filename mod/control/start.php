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
	elgg_load_library('control:tickets');
	
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
			if(!isset($page[1])){
				$page[1] = 'mine';
			}
			switch($page[1]){
				case 'owner':
					if(!isset($page[2])){
						$page[2] = elgg_get_logged_in_user_guid();
					}
					$params = array(
						'content' => control_get_page_tickets_list($page[2]),
					);
					break;
				case 'all':
					$params = array(
						'content' => control_get_page_tickets_list()
					);
					break;
				case 'add':
					$params = array(
						'content' => control_get_page_tickets_add()
					);
					break;
				case 'edit':
					$content = control_get_page_tickets_edit();
					break;
			}
			break;
		default:
			$content = 'Page not found';
	}
	
	$defaults = array(
		'content'=>$content,
	);
	$params = array_merge($default, $params);
	
	$body = elgg_view_layout('content', $params);
	echo elgg_view_page($params['title'], $body);
	
	return true;
}
