<?php
	
	gatekeeper();

	$guid = get_input('guid');
		
	if(!empty($guid) && ($entity = get_entity($guid))) {
		if($entity instanceof Event) {
			$event = $entity;
			
			elgg_push_breadcrumb($event->title, $event->getURL());
			elgg_push_breadcrumb($title_text);
			
			$title_text = elgg_echo("event_manager:registration:programparticipation");
			
			if($event->with_program) {
				$content = $event->getProgramData(elgg_get_logged_in_user_guid(), true);
				
				$content .= elgg_view('input/button', array('type' => 'button', 'id' => 'event_manager_save_program_participation', 'value' => elgg_echo('save')));
			}
		
			$body = elgg_view_layout('one_sidebar', array(
				'filter' => '',
				'content' => $content,
				'title' => $title_text,
			));
			
			echo elgg_view_page($title_text, $body);
		} else {
			forward(EVENT_MANAGER_BASEURL);
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
		forward(EVENT_MANAGER_BASEURL);
	}