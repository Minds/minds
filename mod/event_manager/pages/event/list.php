<?php

	$title_text = elgg_echo("event_manager:list:searchevents");
	
	$event_options = array();
	
	if(($page_owner = elgg_get_page_owner_entity()) && ($page_owner instanceof ElggGroup)){
		group_gatekeeper();
		
		elgg_push_breadcrumb($page_owner->name);
		
		$event_options["container_guid"] = $page_owner->getGUID();
		
		$who_create_group_events = elgg_get_plugin_setting('who_create_group_events', 'event_manager'); // group_admin, members
		if((($who_create_group_events == "group_admin") && $page_owner->canEdit()) || (($who_create_group_events == "members") && $page_owner->isMember($user))){
			elgg_register_menu_item('title', array(
								'name' => "new",
								'href' => "events/event/new/" . $page_owner->getGUID(),
								'text' => elgg_echo("event_manager:menu:new_event"),
								'link_class' => 'elgg-button elgg-button-action',
								));
		}
	} elseif(elgg_is_logged_in()) {
		$who_create_site_events = elgg_get_plugin_setting('who_create_site_events', 'event_manager');
		if($who_create_site_events != 'admin_only' || elgg_is_admin_logged_in()){
			elgg_register_menu_item('title', array(
								'name' => "new",
								'href' => "events/event/new",
								'text' => elgg_echo("event_manager:menu:new_event"),
								'link_class' => 'elgg-button elgg-button-action',
								));
		}
	}
	
	$events = event_manager_search_events($event_options);
	
	$entities = $events["entities"];
	$count = $events["count"];
	
	$form = elgg_view("event_manager/forms/event/search");
	
	$result = elgg_view("event_manager/search_result", array("entities" => $entities, "count" => $count));
	
	$content = 	$form . $result;
	
	$body = elgg_view_layout('content', array(
		'filter' => '',
		'content' => $content,
		'title' => $title_text,
	));
	
	echo elgg_view_page($title_text, $body);
	