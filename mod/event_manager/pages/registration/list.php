<?php 
	gatekeeper();

	$guid = get_input('guid');
	$filter = get_input("filter", "waiting");
	
	if($entity = get_entity($guid))	{	
		if($entity->getSubtype() == Event::SUBTYPE) {
			$event = $entity;
		}
	}
	
	if(!empty($event)) {
		if(!$event->canEdit()) {
			forward($event->getURL());
		} else {
			$registrations = $event->getAllRegistrations($filter);
			
			$list = elgg_view_entity_list($registrations['entities'], 999, 0, false, false);		

			$title_text = elgg_echo("event_manager:event:viewregistrations");
			
			elgg_push_breadcrumb($event->title, $event->getURL());
			elgg_push_breadcrumb($title_text);
			
			$navigation = elgg_view('event_manager/registration_sort_menu', array('eventguid' => $guid, 'filter' => $filter));
			
			$body = elgg_view_layout('one_sidebar', array(
				'filter' => '',
				'content' => $list,
				'title' => $title_text,
			));
			
			echo elgg_view_page($title_text, $body);
		}
	} else {	
		register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
		forward(REFERER);
	}