<?php
/**
 * Library files for tickets, from the control plugin
 */
 
/**
 * (sub) Page handler for tickets 
 * http://mysite.com/control/tickets/{{endpoint}}
 * 
 * @param array $page
 * @return bool
 */
function control_tickets_page_handler($page){
	
	control_tickets_page_setup();
		
	if(!isset($page[0])){
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
				'title' => elgg_echo('control:tickets:add'),
				'content' => control_get_page_tickets_add()
			);
			break;
		case 'edit':
			$params = array(
				'title' => elgg_echo('control:tickets:edit'),
				'content' => control_get_page_tickets_edit()
			);
			break;
		default:
			$content = 'Page not found...';
	}
	
	$defaults = array(
		'content'=>$content,
		'sidebar' => '',
		'hide_ads' => true,
	);
	$params = array_merge($defaults, $params);
	
	$body = elgg_view_layout('content', $params);
	echo elgg_view_page($params['title'], $body);
	
	return true;
}

/**
 * Page setup (for tickets)
 * 
 * Registers page menus
 * @return void
 */
function control_tickets_page_setup(){
	
	elgg_register_title_button('control', 'tickets/add');
	
	$items = array('all', 'owner');
	if(elgg_is_admin_logged_in()){
		array_push($items, 'admin');
	}
	
	foreach($items as $priority => $item){
		elgg_register_menu_item('page', array(
			'name' => $item,
			'text' => elgg_echo("control:tickets:menu:$item"),
			'href' => elgg_get_site_url() . "control/tickets/$item",
			'priority' => $priority
		));
	}
	
	if(elgg_get_page_owner_guid() != 0 && elgg_get_page_owner_guid() != elgg_get_logged_in_user_guid()){
		$page_owner = elgg_get_page_owner_entity();
		elgg_register_menu_item('page', array(
			'name' => 'owner:'.$page_owner,
			'text' => elgg_echo("control:tickets:menu:owner:x", array($page_owner)),
			'href' => elgg_get_site_url() . "control/tickets/owner/$page_owner->username"
		));
	}
	
}

/**
 * Return a list of tickets
 * @todo - allow for filters, such as tags, priority, state
 * 
 * @param $owner_guid - not required
 * @return array
 */
function control_get_page_tickets_list($owner_guid = NULL){
		
	$params = array(
		'type'=>'object',
		'subtype'=>'control_ticket',
		'limit' => get_input('limit', 12),
		'offset' => get_input('offset', ''),
		'full_view' => false
	);
	
	if($owner_guid && ($owner_guid == elgg_get_logged_in_user_guid() || elgg_is_admin_logged_in())){
		$params['owner_guid'] = $owner_guid;
	} 
	
	return elgg_list_entities($params);
}

/**
 * Loads the input form
 */
function control_get_page_tickets_add(){
	return elgg_view_form('control/tickets/add');
}
/**
 * Loads the edit form
 */
function control_get_page_tickets_edit(){
	return elgg_view_form('control/tickets/edit');
}
