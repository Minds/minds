<?php 

	$guid = get_input("guid");
	
	if(!empty($guid) && ($entity = get_entity($guid))) {
		if($entity instanceof Event) {
			$event = $entity;
			if($event && $event->canEdit()) {
				elgg_extend_view('profile/menu/actions', 'event_manager/profile/menu/actions');
				
				$title_text = elgg_echo('event_manager:event:rsvp:waiting_list');
				
				elgg_push_breadcrumb($event->title, $event->getURL());
				elgg_push_breadcrumb($title_text);
				
				if($waiting_list = $event->getEntitiesFromRelationship(EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST)) {
					$content .= '<div class="event_manager_event_view_waitinglist">';
					foreach($waiting_list as $user) {
						$content .= elgg_view("profile/icon", array("entity" => $user, "size" => "small"));
					}
					$content .= '<div class="clearfloat"></div>';
					$content .= '</div>';
				} else {
					$content = elgg_echo('event_manager:event:waitinglist:empty');
				}
				
				$body = elgg_view_layout('one_sidebar', array(
					'filter' => '',
					'content' => $content,
					'title' => $title_text,
				));
				
				echo elgg_view_page($title_text, $body);
			}
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
		forward(REFERER);
	}