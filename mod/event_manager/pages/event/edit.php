<?php 

	gatekeeper();

	$title_text = elgg_echo("event_manager:edit:title");
	
	$guid = get_input("guid");
	
	if(!empty($guid) && ($entity = get_entity($guid))){	
		if($entity->getSubtype() == Event::SUBTYPE)	{
			$event = $entity;
			
			elgg_push_breadcrumb($entity->title, $event->getURL());
			
			elgg_set_page_owner_guid($event->container_guid);
		}
	} else {
		$forward = true;
		$page_owner = elgg_get_page_owner_entity();
		
		if($page_owner && ($page_owner instanceof ElggGroup)){
			$who_create_group_events = elgg_get_plugin_setting('who_create_group_events', 'event_manager'); // group_admin, members
			
			if(!empty($who_create_group_events)){
				if((($who_create_group_events == "group_admin") && $page_owner->canEdit()) || (($who_create_group_events == "members") && $page_owner->isMember($user))){
					$forward = false;  	
				} 
			} 
			
		} else {
			$who_create_site_events = elgg_get_plugin_setting('who_create_site_events', 'event_manager');
			if(($who_create_site_events != 'admin_only') || elgg_is_admin_logged_in()){
				$forward = false;
			}
			elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
		}
		if($forward){
			forward(EVENT_MANAGER_BASEURL);
		}
	}
	
	elgg_push_breadcrumb($title_text);
	
	$form = elgg_view("event_manager/forms/event/edit", array("entity" => $event));

	$body = elgg_view_layout('content', array(
		'filter' => '',
		'content' => $form,
		'title' => $title_text,
	));
	
	echo elgg_view_page($title_text, $body);
	