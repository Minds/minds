<?php
	gatekeeper();
	
	$guid = get_input("guid");

	$title_text = elgg_echo("event_manager:edit:upload:title");
	
	if(!empty($guid) && ($entity = get_entity($guid))) {	
		if($entity->getSubtype() == Event::SUBTYPE) {
			$event = $entity;
		}
	}
	
	if(!empty($event)) {
		
		elgg_push_breadcrumb($event->title, $event->getURL());
		elgg_push_breadcrumb($title_text);
		
		if(!$event->canEdit()) {
			forward($event->getURL());
		}
	
		$form = elgg_view("event_manager/forms/event/upload", array("entity" => $event));
		
		$current_files = elgg_view("event_manager/event/files", array("entity" =>$event));
				
		$page_data = $form . $current_files;
		
		$body = elgg_view_layout('content', array(
			'filter' => '',
			'content' => $page_data,
			'title' => $title_text,
		));
		
		echo elgg_view_page($title_text, $body);
	} else {
		register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
		forward(REFERER);
	}
