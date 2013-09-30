<?php
/**
 * Webinar helper functions
 *
 * @package Webinar
 */

/**
 * Get page components to view a webinar.
 *
 * @param int $guid GUID of a webinar entity.
 * @return array
 */
function webinar_get_page_content_view($guid){
	
	$return = array();
	
	$return = array(
			'filter' => ''
	);
	
	$webinar = get_entity($guid,'object');
	if (!elgg_instanceof($webinar, 'object', 'webinar')) {
		register_error(elgg_echo('webinar:error:not_found'));
		forward(REFERER);
	}
	//set button join, subscribe/unsubscribe and start/top
	webinar_menu_title($guid);
	//set breadcrumb
	$container = $webinar->getContainerEntity();
	$crumbs_title = $container->name;
	if (elgg_instanceof($container, 'group')) {
		elgg_push_breadcrumb($crumbs_title, "webinar/group/$container->guid/all");
	} else {
		elgg_push_breadcrumb($crumbs_title, "webinar/owner/$container->username");
	}
	
	//set Title
	$return['title'] = htmlspecialchars($webinar->title);
	elgg_push_breadcrumb($webinar->title);
	
	$return['content'] .= elgg_view_entity($webinar, array('full_view' => true));
	
	$return['content'] .= elgg_view_comments($webinar);
	
	return $return;
}

/**
 * Get page components to edit/create a webinar.
 *
 * @param string  $action_type     'edit' or 'new'
 * @param int     $guid     GUID of webinar or container
 * @return array
 */
function webinar_get_page_content_edit($action_type, $guid = 0){
	
	$return = array(
			'filter' => '',
	);
	
	if ($action_type == 'edit') {
		$title = elgg_echo("webinar:edit");
		
		$webinar = get_entity($guid, 'object');
		if (elgg_instanceof($webinar, 'object', 'webinar') && $webinar->canEdit()) {
			
			$container = $webinar->getContainerEntity();
			$crumbs_title = $container->name;
			if (elgg_instanceof($container, 'group')) {
				elgg_push_breadcrumb($crumbs_title, "webinar/group/$container->guid/all");
			} else {
				elgg_push_breadcrumb($crumbs_title, "webinar/owner/$container->username");
			}
			elgg_push_breadcrumb($webinar->title, $webinar->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));
			
			$body_vars = webinar_prepare_form_vars($webinar);
			$content = elgg_view_form('webinar/save', array(), $body_vars);
			
		} else {
			register_error(elgg_echo('webinar:error:cannot_edit'));
			forward($webinar->getURL());
		}
	} else {
		
		
		if (!$guid) {
			$container = elgg_get_logged_in_user_entity();
		} else {
			$container = get_entity($guid);
			if (!$container->canEdit()) {
				register_error(elgg_echo('webinar:error:cannot_edit'));
				forward($container->getURL());
			}
		}
		
		$crumbs_title = $container->name;
		if (elgg_instanceof($container, 'group')) {
			$title = elgg_echo('webinar:new:group', array($container->name));
			elgg_push_breadcrumb($crumbs_title, "webinar/group/$container->guid/all");
		} else {
			$title = elgg_echo('webinar:new:user');
			elgg_push_breadcrumb($crumbs_title, "webinar/owner/$container->username");
		}
		elgg_push_breadcrumb(elgg_echo('webinar:add'));
		
		$body_vars = webinar_prepare_form_vars();
		$content = elgg_view_form('webinar/save', array(), $body_vars);
	}
	
	$return['title'] = $title;
	$return['content'] = $content;
	return $return;
}
/**
 * Prepare the new/edit form variables
 *
 * @param ElggObject $webinar
 * @return array
 */
function webinar_prepare_form_vars($webinar = null) {
	$plugin =  elgg_get_calling_plugin_entity();
	// input names => defaults
	$values = array(
			'title' => '',
			'description' => '',
			'access_id' => ACCESS_DEFAULT,
			'tags' => '',
			'status' => 'upcoming',
			'fee' => '',
			'enterprise' => 0,
			'server_salt' => $plugin->server_salt,
			'server_url' => $plugin->server_url,
			'logout_url' => null,
			'admin_pwd' => $plugin->admin_pwd,
			'user_pwd' => $plugin->user_pwd,
			'container_guid' => elgg_get_page_owner_guid(),
			'guid' => null
	);
	// if webinar exists, populate form with his values
	if ($webinar) {
		foreach (array_keys($values) as $field) {
			if (isset($webinar->$field)) {
				$values[$field] = $webinar->$field;
			}
		}
	}
	// overwrite by a form saved in this session
	if (elgg_is_sticky_form('webinar')) {
		$sticky_values = elgg_get_sticky_values('webinar');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('webinar');

	return $values;
}

/**
 * Get page components to list a user's or all webinars.
 * 
 * @param array  $page 
 * @return array
 */
function webinar_get_page_content_list($page = array()){
	
	//compilcated input analysing
	$guid_type = $page[0];
	$href = "webinar/$guid_type";
	if(isset($page[1])){
		if($guid_type == 'all'){
			$guid = 0;
			$filter = $page[1];
		}else if($guid_type == 'owner' || $guid_type == 'friends'){
			$user = get_user_by_username($page[1]);
			if($user){
				$guid = $user->getGUID();
			}else{
				register_error(elgg_echo('webinar:error:not_user'));
				forward("webinar/all");
			}
			$href .=  "/$page[1]";
			$filter = 'all';
		}else if($guid_type = 'group' && is_numeric($page[1])){
			$guid = $page[1];
			$href .=  "/$page[1]";
			$filter = 'all';
		}else{
			register_error(elgg_echo('webinar:error:wrong_request'));
			forward("webinar/all");
		}
		if(isset($page[2])){
			$filter = $page[2];
		}
	}else{
		$guid = 0;
		$filter = 'all';
	}
	
	
	$return = array();
	
	$options = array(
			'type' => 'object',
			'subtypes' => array('webinar'),
			'full_view' => FALSE,
			'limit' => 12
	);

	if($guid_type == 'owner'){
		$options['owner_guid'] = $guid;
	}

	if($guid_type == 'friends'){
		$options['network'] = $guid;
	}
	
	$filters = array('all','upcoming','running','done');
	
	//if is valid filter set options for calling metadata search 
	if($filter != 'all' && in_array($filter, $filters)){
		$options['metadata_names'] = 'status';
		$options['metadata_values'] = $filter;

	}
	
	//register menu page
	foreach ($filters as $f) {
		elgg_register_menu_item('page', array(
				'name' => "webinar:$f",
				'text' => elgg_echo("webinar:menu:page:$f"),
				'href' => ($f == 'all' ?$href:"$href/$f"),
				'selected' => ($f == $filter)
		));
	}
	
	if(!$guid){
		$return['filter_context'] = 'all';
		$return['title'] = elgg_echo('webinar:webinars');
		//remove the link in the breadcrumb
		elgg_pop_breadcrumb();
		elgg_push_breadcrumb(elgg_echo('webinar:webinars'));
		//create webinar button
		elgg_register_title_button();
	}else{
		$entity = get_entity($guid);
		if($entity){
			switch($guid_type){
				case 'owner':
				case 'group':
					//set options
					$options['container_guid'] = $guid;
					
					//set filter
					if ($guid == elgg_get_logged_in_user_guid()) {
						$return['filter_context'] = 'mine';
						$return['title'] = elgg_echo('webinar:webinars');
						elgg_register_title_button();
					} else if (elgg_instanceof($entity, 'group')) {
						// access check for closed groups
						group_gatekeeper();
						$return['filter'] = false;
						$return['title'] = elgg_echo('webinar:title:group:all', array($entity->name));
						elgg_register_title_button();
					}else{
						// do not show button or select a tab when viewing someone else's posts
						$return['filter'] = false;
						$return['title'] = elgg_echo('webinar:webinars');
					}
					
					//set breadcrumb
					elgg_push_breadcrumb($entity->name, $entity->getURL());
					elgg_push_breadcrumb(elgg_echo('webinar:all'));
					break;
				case 'friends':
					//set options
					if (!$friends = get_user_friends($entity->getGUID(), ELGG_ENTITIES_ANY_VALUE, 0)) {
						register_error(elgg_echo('friends:none:you'));
						forward("webinar/all");
					} else {
						foreach ($friends as $friend) {
							$options['container_guids'][] = $friend->getGUID();
						}
					}
					
					//set filter
					if ($guid == elgg_get_logged_in_user_guid()) {
						$return['filter_context'] = 'friend';
						$return['title'] = elgg_echo('webinar:webinars');
					}else{
						$return['filter'] = false;
						$return['title'] = elgg_echo('webinar:title:friend:user', array($entity->name));
					}
					//set breadcrumb
					elgg_push_breadcrumb($entity->name, "webinar/owner/{$entity->username}");
					elgg_push_breadcrumb(elgg_echo('friends'));
					break;
				case 'all':
				default:
					register_error(elgg_echo('webinar:error:not_found'));
					forward("webinar/all");
					break;
			}
		}else{
			register_error(elgg_echo('webinar:error:not_found'));
			forward("webinar/all");
		}
		
	}
	$list = elgg_list_entities($options);
	if (!$list) {
		$return['content'] = elgg_echo('webinar:error:not_found');
	} else {
		$return['content'] = $list;
	}
	
	return $return;
	
}

function webinar_get_page_content_relationships($relationship, $guid){
return;	
	$return = array();
	
	$return = array(
			'filter' => '',
	);
	
	$webinar = get_entity($guid);
	
	if (!$webinar || !elgg_instanceof($webinar, 'object', 'webinar')) {
		register_error(elgg_echo('webinar:error:not_found'));
		forward(REFERER);
	}
	
	//set Title
	$title = htmlspecialchars($webinar->title);
	$return['title'] = elgg_echo("webinar:members:$relationship:title", array($title));
	
	//set breadcrumb
	$container = $webinar->getContainerEntity();
	$crumbs_title = $container->name;
	if (elgg_instanceof($container, 'group')) {
		elgg_push_breadcrumb($crumbs_title, "webinar/group/$container->guid/all");
	} else {
		elgg_push_breadcrumb($crumbs_title, "webinar/owner/$container->username");
	}
	elgg_push_breadcrumb($title, $webinar->getURL());
	elgg_push_breadcrumb(elgg_echo("webinar:members:$relationship"));
	
	$list = elgg_list_entities_from_relationship(array(
			'relationship' => $relationship,
			'relationship_guid' => $webinar->getGUID(),
			'inverse_relationship' => true,
			'types' => 'user',
			'limit' => 20,
	));
	
	if (!$list) {
		$return['content'] = elgg_echo('webinar:members:no');
	} else {
		$return['content'] = $list;
	}
	
	return $return;
	
}

function webinar_get_free_slots($container_guid, $limit = 1){
	$slots = array();
	$offset = 0;
	while(count($slots) < $limit){	
		$slot = get_next_slot($offset);
		if(webinar_is_free($slot,$container_guid)){
			$slots[] = $slot;
		}
		$offset++;
	}
	return $slots;
}
function webinar_is_free($slot,$container_guid){
	return event_calendar_get_events_between($slot->start_date, $slot->end_date, true, 10, 0,$container_guid) > 0 ? false : true ;
}
function webinar_get_next_slot($offset = 0){
	$nowDayOfWeek = date('w');
	$nowDayOfYear = date('z');
	$delta = WEBINAR_MEETING_SLOT_DAY - $nowDayOfWeek;
	if ($delta <= 0 ){
		$offset += 1;
	}
	$slotDayOfYear = $nowDayOfYear + $offset*7 + $delta;
	$dateTime = date_create_from_format('z', $slotDayOfYear);
	$date = $dateTime->format('Y-m-d');
	$timestamp = strtotime($date . ' 00:00:00');
	$slot = new stdClass();
	$slot->start_time = WEBINAR_MEETING_SLOT_TIME_START*60;
	$slot->end_time = WEBINAR_MEETING_SLOT_TIME_END*60;
	$slot->start_date = $timestamp + 60*$slot->start_time;
	$slot->end_date = $timestamp + 60*$slot->end_time;
	return $slot;
}

function webinar_subscribe($webinar_guid, $user_guid) {
	$result = elgg_trigger_plugin_hook('webinar:subscribe', 'webinar', array('webinar' => get_entity($webinar_guid), 'user' => get_entity($user_guid)),true);
	if($result){
		return add_entity_relationship($user_guid, 'registered', $webinar_guid);
	}else{
		return false;
	}
}
function webinar_unsubscribe($webinar_guid, $user_guid) {
	$result = elgg_trigger_plugin_hook('webinar:unsubscribe', 'webinar', array('webinar' => get_entity($webinar_guid), 'user' => get_entity($user_guid)),true);
	if($result){
		return remove_entity_relationship($user_guid, 'registered', $webinar_guid);
	}else{
		return false;
	}
}
function webinar_join($webinar_guid, $user_guid){
	$result = elgg_trigger_plugin_hook('webinar:join', 'webinar', array('webinar' => get_entity($webinar_guid,'object'), 'user' => get_entity($user_guid,'user')),true);
	if($result){
		$webinar = get_entity($webinar_guid,'object');
		$members = unserialize($webinar->members);
		if(!is_array($members)){ $members = array(); }
		array_push($members, $user_guid);
		$webinar->members = serialize($members);
		$webinar->save();
	}else{
		return false;
	}
}
function webinar_is_registered($webinar_guid, $user_guid) {
	$object = check_entity_relationship($user_guid, 'registered', $webinar_guid);
	if ($object) {
		return true;
	} else {
		return false;
	}
}
function webinar_is_attendee($webinar_guid, $user_guid) {
	$object = check_entity_relationship($user_guid, 'attendee', $webinar_guid);
	if ($object) {
		return true;
	} else {
		return false;
	}
}
/** 
 * Get members
 */
function webinar_get_members($webinar_guid){
	$webinar = get_entity($webinar_guid,'object');
	$members = unserialize($webinar->members);
	$return = array();
	foreach ($members as $member_guid){
		$return[] = get_entity($member_guid, 'user');
	}
	return $return;
}
/**
 * Register menu title for webinar view
 *
 * @param int     $webinar_guid     GUID of webinar or container
 * @return true
 */
function webinar_menu_title($webinar_guid){
	$webinar = get_entity($webinar_guid);
	$user = elgg_get_logged_in_user_entity();
	
	if(elgg_is_admin_logged_in() || ($user && $user->getGUID() == $webinar->getOwnerGUID())) {
		if ($webinar->isUpcoming()){
			//start button
			elgg_register_menu_item('title', array(
					'name' => 'start',
					'href' => "action/webinar/start?webinar_guid={$webinar->getGUID()}",
					'text' => elgg_echo("webinar:start"),
					'is_action' => true,
					'link_class' => 'elgg-button elgg-button-action',
					));
		}else if ($webinar->isRunning()) {
			//stop button
			elgg_register_menu_item('title', array(
					'name' => 'stop',
					'href' => "action/webinar/stop?webinar_guid={$webinar->getGUID()}",
					'text' => elgg_echo("webinar:stop"),
					'is_action' => true,
					'link_class' => 'elgg-button elgg-button-action',
					));
		}
	}
	// this page is public but actions need logged user
	if($user){
		if ($webinar->isUpcoming()){
			if ($webinar->isRegistered($user)) {
				//unsubscribe button
				elgg_register_menu_item('title', array(
						'name' => 'unsubscribe',
						'href' => "action/webinar/unsubscribe?webinar_guid={$webinar->getGUID()}",
						'text' => elgg_echo("webinar:unsubscribe"),
						'is_action' => true,
						'link_class' => 'elgg-button elgg-button-action',
						));
			} else {
				elgg_register_menu_item('title', array(
						'name' => 'subscribe',
						'href' => "action/webinar/subscribe?webinar_guid={$webinar->getGUID()}",
						'text' => $webinar->fee > 0 && !webinar_has_paid(elgg_get_logged_in_user_guid(), $webinar->getGUID()) ? elgg_echo("webinar:subscribe:fee", array($webinar->fee)) : elgg_echo("webinar:subscribe"),
						'is_action' => true,
						'link_class' => 'elgg-button elgg-button-action',
						));
			}
		}else if ($webinar->isRunning()) {
			elgg_register_menu_item('title', array(
					'name' => 'join',
					'href' => "action/webinar/join?webinar_guid={$webinar->getGUID()}",
					'text' => $webinar->fee > 0 && !webinar_has_paid(elgg_get_logged_in_user_guid(), $webinar->getGUID()) ? elgg_echo("webinar:join:fee", array($webinar->fee)) : elgg_echo("webinar:join"),
					'is_action' => true,
					'link_class' => 'elgg-button elgg-button-action',
					'target'=>'_blank',
					));
		}
	}
	return true;
}
/** 
 * Returns bool to whether or not a user has paid for access to the event
 */
function webinar_has_paid($user_guid, $webinar_guid){
	//find the relationship between the user and the webinar
	/*elgg_get_entities_from_relationship(	array(	'relationship_guid'	=> $user_guid,
													'relationship' => 
									));*/
/*	$results = elgg_get_entities( array('type' => 'object',
													'subtype' => 'pay',
													'owner_guid' => $user_guid,
													'metadata_name_value_pairs' => array(	array(	'name'=> 'object_guid',
																									'value'=> $webinar_guid),
																							array(	'name'=> 'status',
																									'value'=> 'confirmed')
																						),
									));
	
	if(count($results) > 0){
		return true;
	} else {
		return false;
	}*/
	return false;
}
/*
 function get_webinar_relationship($relationship, $webinar_guid, $limit = 10, $offset = 0, $site_guid = 0, $count = false) {

// in 1.7 0 means "not set."  rewrite to make sense.
if (!$site_guid) {
$site_guid = ELGG_ENTITIES_ANY_VALUE;
}

return elgg_get_entities_from_relationship(array(
		'relationship' => $relationship,
		'relationship_guid' => $webinar_guid,
		'inverse_relationship' => TRUE,
		'types' => 'user',
		'limit' => $limit,
		'offset' => $offset,
		'count' => $count,
		'site_guid' => $site_guid
));
}*/
