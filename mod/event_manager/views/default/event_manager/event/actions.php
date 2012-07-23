<?php 

	$event = $vars["entity"];

	$options = array();
	
	$context = elgg_get_context();
	
	if($event->canEdit()) {
		if($tools = elgg_view("event_manager/event/tools", $vars)){
			$options[] = $tools;
		}	
	}
	
	if(elgg_is_logged_in()){
		if($rsvp = elgg_view("event_manager/event/rsvp", $vars)){
			$options[] = $rsvp;
		}

		if(!in_array($context, array("widgets", "maps"))){
			if($registration = elgg_view("event_manager/event/registration", $vars)){
				$options[] = $registration;
			}
		}		
	} else {
		if($event->register_nologin) {
			$register_link = EVENT_MANAGER_BASEURL . '/event/register/'.$event->getGUID();
			
			$options[] = elgg_view('output/url', array("class" => "elgg-button elgg-button-submit", "href" => $register_link, "text" => elgg_echo('event_manager:event:register:register_link')));
		}
	}

	if($event->show_attendees){
		$attending_count = 0;
		if($count = $event->getRelationships(true)){
			if(array_key_exists(EVENT_MANAGER_RELATION_ATTENDING, $count)) {
				$attending_count = $count[EVENT_MANAGER_RELATION_ATTENDING];
			} 
		}
		
		$options[] = $attending_count . " ". strtolower(elgg_echo("event_manager:event:relationship:event_attending"));
	}	
	
	echo implode(" | ", $options);