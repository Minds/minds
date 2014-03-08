<?php

elgg_load_library('bblr');
/**
 * Get page components to view a webinar.
 *
 * @param int $guid GUID of a webinar entity.
 * @return array
 */
function gatherings_get_page_content_view($guid){
	
	$return = array();
	
	$return = array(
			'filter' => ''
	);
	
	$gathering = get_entity($guid);
	if (!elgg_instanceof($gathering, 'object', 'gathering')) {
		register_error(elgg_echo('gatherings:error:not_found'));
		forward(REFERER);
	}
	
	//set breadcrumb
	$container = $gathering->getContainerEntity();
	$crumbs_title = $container->name;
	if (elgg_instanceof($container, 'group')) {
		elgg_push_breadcrumb($crumbs_title, "gatherings/group/$container->guid/all");
	} else {
		elgg_push_breadcrumb($crumbs_title, "gatherings/owner/$container->username");
	}
	
	//set Title
	$return['title'] = $gathering->title;
	elgg_push_breadcrumb($gathering->title);
	
	$return['content'] .= elgg_view_entity($gathering, array('full_view' => true));
		
	return $return;
}

/**
 * Get page components to edit/create a webinar.
 *
 * @param string  $action_type     'edit' or 'new'
 * @param int     $guid     GUID of webinar or container
 * @return array
 */
function gatherings_get_page_content_edit($action_type, $guid = 0){
	
	$return = array(
			'filter' => '',
	);
	
	if ($action_type == 'edit') {
		$title = elgg_echo("gatherings:edit");
		
		$webinar = get_entity($guid);
		if (elgg_instanceof($gathering, 'object', 'gathering') && $gathering->canEdit()) {
			
			$container = $webinar->getContainerEntity();
			$crumbs_title = $container->name;
			if (elgg_instanceof($container, 'group')) {
				elgg_push_breadcrumb($crumbs_title, "webinar/group/$container->guid/all");
			} else {
				elgg_push_breadcrumb($crumbs_title, "webinar/owner/$container->username");
			}
			elgg_push_breadcrumb($webinar->title, $webinar->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));
			
			$body_vars = gatherings_prepare_form_vars($webinar);
			$content = elgg_view_form('webinar/save', array(), $body_vars);
			
		} else {
			register_error(elgg_echo('gatherings:error:cannot_edit'));
			forward($webinar->getURL());
		}
	} else {
		
		//add new
		
		if (!$guid) {
			$container = elgg_get_logged_in_user_entity();
		} else {
			$container = get_entity($guid);
			if (!$container->canEdit()) {
				register_error(elgg_echo('gatherings:error:cannot_edit'));
				forward($container->getURL());
			}
		}
		
		$crumbs_title = $container->name;
		if (elgg_instanceof($container, 'group')) {
			$title = elgg_echo('gatherings:new:group', array($container->name));
			elgg_push_breadcrumb($crumbs_title, "webinar/group/$container->guid/all");
		} else {
			$title = elgg_echo('gatherings:new:user');
			elgg_push_breadcrumb($crumbs_title, "webinar/owner/$container->username");
		}
		elgg_push_breadcrumb(elgg_echo('gatherings:add'));
		
		$body_vars = gatherings_prepare_form_vars();
		$content = elgg_view_form('gatherings/save', array(), $body_vars);
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
function gatherings_prepare_form_vars($webinar = null) {
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
function gatherings_get_page_content_list($page = array()){

	//compilcated input analysing
	$guid_type = $page[0];
	$href = "gatherings/$guid_type";
	
	if(isset($page[1])){
		if($guid_type == 'all'){
			$guid = 0;
			$filter = $page[1];
		}else if($guid_type == 'owner' || $guid_type == 'friends'){
			$user = get_user_by_username($page[1]);
			if($user){
				$guid = $user->getGUID();
			}else{
				register_error(elgg_echo('gatherings:error:not_user'));
				forward("webinar/all");
			}
			$href .=  "/$page[1]";
			$filter = 'all';
		}else if($guid_type = 'group' && is_numeric($page[1])){
			$guid = $page[1];
			$href .=  "/$page[1]";
			$filter = 'all';
		}else{
			register_error(elgg_echo('gatherings:error:wrong_request'));
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
			'subtype' => 'gathering',
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
				'name' => "gatherings:$f",
				'text' => elgg_echo("gatherings:menu:page:$f"),
				'href' => ($f == 'all' ?$href:"$href/$f"),
				'selected' => ($f == $filter)
		));
	}
	
	if(!$guid){
		$return['filter_context'] = 'all';
		$return['title'] = elgg_echo('gatherings:webinars');
		//remove the link in the breadcrumb
		elgg_pop_breadcrumb();
		elgg_push_breadcrumb(elgg_echo('gatherings:webinars'));
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
						$return['title'] = elgg_echo('gatherings:webinars');
						elgg_register_title_button();
					} else if (elgg_instanceof($entity, 'group')) {
						// access check for closed groups
						group_gatekeeper();
						$return['filter'] = false;
						$return['title'] = elgg_echo('gatherings:title:group:all', array($entity->name));
						elgg_register_title_button();
					}else{
						// do not show button or select a tab when viewing someone else's posts
						$return['filter'] = false;
						$return['title'] = elgg_echo('gatherings:webinars');
					}
					
					//set breadcrumb
					elgg_push_breadcrumb($entity->name, $entity->getURL());
					elgg_push_breadcrumb(elgg_echo('gatherings:all'));
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
						$return['title'] = elgg_echo('gatherings:webinars');
					}else{
						$return['filter'] = false;
						$return['title'] = elgg_echo('gatherings:title:friend:user', array($entity->name));
					}
					//set breadcrumb
					elgg_push_breadcrumb($entity->name, "webinar/owner/{$entity->username}");
					elgg_push_breadcrumb(elgg_echo('friends'));
					break;
				case 'all':
				default:
					register_error(elgg_echo('gatherings:error:not_found'));
					forward("webinar/all");
					break;
			}
		}else{
			register_error(elgg_echo('gatherings:error:not_found'));
			forward("webinar/all");
		}
		
	}
	$list = elgg_list_entities($options);
	if (!$list) {
		$return['content'] = elgg_echo('gatherings:error:not_found');
	} else {
		$return['content'] = $list;
	}
	
	return $return;
	
}

function gatherings_get_page_content_relationships($relationship, $guid){
return;	
	$return = array();
	
	$return = array(
			'filter' => '',
	);
	
	$webinar = get_entity($guid);
	
	if (!$webinar || !elgg_instanceof($webinar, 'object', 'webinar')) {
		register_error(elgg_echo('gatherings:error:not_found'));
		forward(REFERER);
	}
	
	//set Title
	$title = htmlspecialchars($webinar->title);
	$return['title'] = elgg_echo("gatherings:members:$relationship:title", array($title));
	
	//set breadcrumb
	$container = $webinar->getContainerEntity();
	$crumbs_title = $container->name;
	if (elgg_instanceof($container, 'group')) {
		elgg_push_breadcrumb($crumbs_title, "webinar/group/$container->guid/all");
	} else {
		elgg_push_breadcrumb($crumbs_title, "webinar/owner/$container->username");
	}
	elgg_push_breadcrumb($title, $webinar->getURL());
	elgg_push_breadcrumb(elgg_echo("gatherings:members:$relationship"));
	
	$list = elgg_list_entities_from_relationship(array(
			'relationship' => $relationship,
			'relationship_guid' => $webinar->getGUID(),
			'inverse_relationship' => true,
			'types' => 'user',
			'limit' => 20,
	));
	
	if (!$list) {
		$return['content'] = elgg_echo('gatherings:members:no');
	} else {
		$return['content'] = $list;
	}
	
	return $return;
	
}

function gatherings_get_free_slots($container_guid, $limit = 1){
	$slots = array();
	$offset = 0;
	while(count($slots) < $limit){	
		$slot = get_next_slot($offset);
		if(gatherings_is_free($slot,$container_guid)){
			$slots[] = $slot;
		}
		$offset++;
	}
	return $slots;
}
function gatherings_is_free($slot,$container_guid){
	return event_calendar_get_events_between($slot->start_date, $slot->end_date, true, 10, 0,$container_guid) > 0 ? false : true ;
}
function gatherings_get_next_slot($offset = 0){
	$nowDayOfWeek = date('w');
	$nowDayOfYear = date('z');
	$delta = gatherings_MEETING_SLOT_DAY - $nowDayOfWeek;
	if ($delta <= 0 ){
		$offset += 1;
	}
	$slotDayOfYear = $nowDayOfYear + $offset*7 + $delta;
	$dateTime = date_create_from_format('z', $slotDayOfYear);
	$date = $dateTime->format('Y-m-d');
	$timestamp = strtotime($date . ' 00:00:00');
	$slot = new stdClass();
	$slot->start_time = gatherings_MEETING_SLOT_TIME_START*60;
	$slot->end_time = gatherings_MEETING_SLOT_TIME_END*60;
	$slot->start_date = $timestamp + 60*$slot->start_time;
	$slot->end_date = $timestamp + 60*$slot->end_time;
	return $slot;
}

function gatherings_subscribe($gatherings_guid, $user_guid) {
	$result = elgg_trigger_plugin_hook('gatherings:subscribe', 'webinar', array('webinar' => get_entity($gatherings_guid), 'user' => get_entity($user_guid)),true);
	if($result){
		return add_entity_relationship($user_guid, 'registered', $gatherings_guid);
	}else{
		return false;
	}
}
function gatherings_unsubscribe($gatherings_guid, $user_guid) {
	$result = elgg_trigger_plugin_hook('gatherings:unsubscribe', 'webinar', array('webinar' => get_entity($gatherings_guid), 'user' => get_entity($user_guid)),true);
	if($result){
		return remove_entity_relationship($user_guid, 'registered', $gatherings_guid);
	}else{
		return false;
	}
}
function gatherings_join($gatherings_guid, $user_guid){
	$result = elgg_trigger_plugin_hook('gatherings:join', 'webinar', array('webinar' => get_entity($gatherings_guid,'object'), 'user' => get_entity($user_guid,'user')),true);
	if($result){
		$webinar = get_entity($gatherings_guid,'object');
		$members = unserialize($webinar->members);
		if(!is_array($members)){ $members = array(); }
		array_push($members, $user_guid);
		$webinar->members = serialize($members);
		$webinar->save();
	}else{
		return false;
	}
}
function gatherings_is_registered($gatherings_guid, $user_guid) {
	$object = check_entity_relationship($user_guid, 'registered', $gatherings_guid);
	if ($object) {
		return true;
	} else {
		return false;
	}
}
function gatherings_is_attendee($gatherings_guid, $user_guid) {
	$object = check_entity_relationship($user_guid, 'attendee', $gatherings_guid);
	if ($object) {
		return true;
	} else {
		return false;
	}
}
/** 
 * Get members
 */
function gatherings_get_members($gatherings_guid){
	$webinar = get_entity($gatherings_guid,'object');
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
 * @param int     $gatherings_guid     GUID of webinar or container
 * @return true
 */
function gatherings_menu_title($gatherings_guid){
	$webinar = get_entity($gatherings_guid);
	$user = elgg_get_logged_in_user_entity();
	
	if(elgg_is_admin_logged_in() || ($user && $user->getGUID() == $webinar->getOwnerGUID())) {
		if ($webinar->isUpcoming()){
			//start button
			elgg_register_menu_item('title', array(
					'name' => 'start',
					'href' => "action/webinar/start?gatherings_guid={$webinar->getGUID()}",
					'text' => elgg_echo("gatherings:start"),
					'is_action' => true,
					'link_class' => 'elgg-button elgg-button-action',
					));
		}else if ($webinar->isRunning()) {
			//stop button
			elgg_register_menu_item('title', array(
					'name' => 'stop',
					'href' => "action/webinar/stop?gatherings_guid={$webinar->getGUID()}",
					'text' => elgg_echo("gatherings:stop"),
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
						'href' => "action/webinar/unsubscribe?gatherings_guid={$webinar->getGUID()}",
						'text' => elgg_echo("gatherings:unsubscribe"),
						'is_action' => true,
						'link_class' => 'elgg-button elgg-button-action',
						));
			} else {
				elgg_register_menu_item('title', array(
						'name' => 'subscribe',
						'href' => "action/webinar/subscribe?gatherings_guid={$webinar->getGUID()}",
						'text' => $webinar->fee > 0 && !gatherings_has_paid(elgg_get_logged_in_user_guid(), $webinar->getGUID()) ? elgg_echo("gatherings:subscribe:fee", array($webinar->fee)) : elgg_echo("gatherings:subscribe"),
						'is_action' => true,
						'link_class' => 'elgg-button elgg-button-action',
						));
			}
		}else if ($webinar->isRunning()) {
			elgg_register_menu_item('title', array(
					'name' => 'join',
					'href' => "action/webinar/join?gatherings_guid={$webinar->getGUID()}",
					'text' => $webinar->fee > 0 && !gatherings_has_paid(elgg_get_logged_in_user_guid(), $webinar->getGUID()) ? elgg_echo("gatherings:join:fee", array($webinar->fee)) : elgg_echo("gatherings:join"),
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
function gatherings_has_paid($user_guid, $gatherings_guid){
	return false;
}

