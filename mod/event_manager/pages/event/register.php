<?php
	$guid = get_input("guid");
	$relation = get_input("relation");
	
	if(!empty($guid) && ($entity = get_entity($guid))) {
		if($entity->getSubtype() == Event::SUBTYPE) {
			$event = $entity;
		
			if(!$event->registration_needed) {
				system_message(elgg_echo('event_manager:registration:message:registrationnotneeded'));
				forward($event->getURL());
			}
			
			if(!elgg_is_logged_in()) {
				if(!$event->hasEventSpotsLeft() || !$event->hasSlotSpotsLeft()) {
					if($event->waiting_list_enabled && $event->registration_needed && $event->openForRegistration()) {
						forward(EVENT_MANAGER_BASEURL.'/event/waitinglist/'.$guid);
					} else {
						register_error(elgg_echo('event_manager:event:rsvp:nospotsleft'));
						forward(REFERER);
					}
				}
			}
				
			$form = $event->generateRegistrationForm();

			$title_text = elgg_echo("event_manager:registration:register:title");
			
			elgg_set_page_owner_guid($event->getContainerGUID());
			
			elgg_push_breadcrumb($event->title, $event->getURL());
			elgg_push_breadcrumb($title_text);
			
			$title = $title_text . " '" . $event->title . "'";
			
			$body = elgg_view_layout('content', array(
				'filter' => '',
				'content' => $form,
				'title' => $title,
			));
			
			echo elgg_view_page($title, $body);
			
			// TODO: replace with sticky form functionality
			$_SESSION['registerevent_values'] = null;
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
		forward(REFERER);
	}