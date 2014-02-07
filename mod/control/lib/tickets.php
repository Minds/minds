<?php
/**
 * Library files for tickets, from the control plugin
 */
 
/**
 * Tickets init
 */
function control_tickets_init(){
	add_subtype('object', 'control_ticket', 'MindsControlTicket');
	elgg_extend_view('css/elgg', 'control_tickets/css');
	elgg_register_entity_url_handler('object', 'control_ticket', 'control_ticket_url_handler');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'control_tickets_entity_menu_setup',99999);
	
	elgg_register_event_handler('comment:create', 'comment', 'control_tickets_comment_event_hook');
	
	control_tickets_page_setup();
	
	$actions_path = elgg_get_plugins_path() . 'control/actions/control/tickets';
	elgg_register_action('control/tickets/add', "$actions_path/update_status.php");
	elgg_register_action('control/tickets/add', "$actions_path/add.php");
	elgg_register_action('control/tickets/edit', "$actions_path/edit.php");
	elgg_register_action('control_tickets/delete', "$actions_path/delete.php");
	
}
 
/**
 * (sub) Page handler for tickets 
 * http://mysite.com/control/tickets/{{endpoint}}
 * 
 * @param array $page
 * @return bool
 */
function control_tickets_page_handler($page){
		
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
		case 'view':
			$ticket = get_entity($page[2]);
			$params = array(
				'title' => $ticket->title,
				'subtitle'=> elgg_view_friendly_time($ticket->time_created),
				'buttons' => elgg_view_menu('entity', array( 
					'entity'=>$ticket, 
					'handler'=>'control_tickets',
					'sort_by' => 'priority',
                	'class' => 'elgg-menu-hz'
				)),
				'content' => elgg_view_entity($ticket, array('full_view'=>true)) . elgg_view_comments($ticket)
			);
			break;
		case 'add':
			if(isset($page[2]) && $page[2]== 'lightbox'){
				echo control_get_page_tickets_add();
				exit;
			}
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
		'title' => elgg_echo('control:tickets'),
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
	
	if(elgg_get_context() == 'control'){
		elgg_register_title_button('control', 'tickets/add');
		
		$items = array('all', 'owner');
		if(elgg_is_admin_logged_in()){
			//array_push($items, 'admin');
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
	} elseif(elgg_get_context() == 'settings') {
		elgg_register_menu_item('page', array(
				'name' => 'tickets',
				'text' => elgg_echo("control:tickets:menu:owner"),
				'href' => elgg_get_site_url() . "control/tickets/owner/"
			));
	}	
	
	/**
	 * footer
	 */
	elgg_load_js('lightbox');
	elgg_load_css('lightbox');
	 
	elgg_register_menu_item('footer', array(
			'name' => 'create:ticket',
			'href' => '/control/tickets/add/lightbox',
			'class' => 'elgg-lightbox',
			'text' => 'Create a ticket'
	));
	
}

/**
 * Menu entity handler
 */
function control_tickets_entity_menu_setup($hook, $type, $return, $params) {
	
	$entity = $params['entity'];

	switch($entity->subtype){
		case 'control_ticket':
			foreach($return as $k => $v){
				if($return[$k]->getName() == 'feature' || $return[$k]->getName() == 'edit'){;			
					unset($return[$k]);
				}
			}
			$return[] = new ElggMenuItem('status', elgg_echo('control:tickets:status:'.$entity->status), '#');
			break;
	}
	
	return $return;
}

/**
 * Url handler
 */
function control_ticket_url_handler($entity){
	return "control/tickets/view/$entity->guid";
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
		'full_view' => false,
		'masonry' => false,
		'list_class' => 'x1 control_tickets'
	);
	
	if($owner_guid && ($owner_guid == elgg_get_logged_in_user_guid() || elgg_is_admin_logged_in())){
		$params['owner_guid'] = $owner_guid;
	} 
	
	return elgg_list_entities($params);
}

/**
 * Load a ticket page
 */
function control_get_page_tickets_view($guid){
	$ticket = get_entity($guid);
	return elgg_view_entity($ticket, array('full_view'=>true));
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

/**
 * Comments event hook
 */
function control_tickets_comment_event_hook($event, $type, $data){
	$ticket = get_entity($data['_source']['pid']);
	$ticket->comment($data['_source']['description'], $data['_source']['owner_guid']);
}
