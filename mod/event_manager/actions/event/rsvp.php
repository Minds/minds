<?php 

	$guid = (int) get_input("guid");
	$user_guid = get_input("user", elgg_get_logged_in_user_guid());
	
	$forward_url = REFERER;
	$notice = true;
	
	if(!empty($guid) && ($event = get_entity($guid))) {
		if($event->getSubtype() == Event::SUBTYPE) {
			
			if(($user = get_entity($user_guid)) && ($rel = get_input("type"))) {
				//echo '- loggedin and relation type is set<br />';
				if($rel == EVENT_MANAGER_RELATION_ATTENDING) {
					//echo '- relation type is \'attending\'<br />';
					if($event->hasEventSpotsLeft() && $event->hasSlotSpotsLeft()) {
						//echo '- event and it\'s slots has spots left<br />';
						if($event->registration_needed) {
							//echo '- forward to event registration<br />';
							$forward_url = EVENT_MANAGER_BASEURL . '/event/register/' . $guid . '/' . $rel;
							$notice = false;
						} else {
							//echo '- no registration needed, rsvp immediately<br />';
							$rsvp = $event->rsvp($rel, $user_guid);
						}						
					} else {
						//echo '- no spots left for this event, nor it\'s slots<br />';
						if($event->waiting_list_enabled) {
							$rel = EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST;
							//echo '- waiting list is enabled<br />';							
							if($event->openForRegistration()) {
								//echo '- event is open for registration (datewise)<br />';	
								if($event->registration_needed) {							
									if($registration = $event->generateRegistrationForm()) {
										//echo '- event CAN generate a registration form<br />';
										//echo '- show normal event waiting list<br />';
										$forward_url = EVENT_MANAGER_BASEURL . '/event/waitinglist/' . $guid;
										$notice = false;
									} else {
										//echo '- cant generate registration form<br />';
										register_error(elgg_echo('event_manager:event:register:no_registrationform'));
									}
								} else {
									$rsvp = $event->rsvp($rel, $user_guid);
								}
							} else {
								//echo 'event is closed for registration, either the registration is set as ended, or the end date has been reached<br />';
								register_error(elgg_echo('event_manager:event:rsvp:registration_ended'));
							}
						} else {
							//echo '- waitinglist disabled, no registration form created, show error and forward back<br />';
							register_error(elgg_echo('event_manager:event:rsvp:nospotsleft'));
						}
					}
				} else {
					//echo '- relation ship type is not EVENT_MANAGER_RELATION_ATTENDING, rsvp otherwise<br />';
					if($event->$rel || ($rel == EVENT_MANAGER_RELATION_UNDO) || ($rel == EVENT_MANAGER_RELATION_ATTENDING)) {
						$rsvp = $event->rsvp($rel, $user_guid);
					} else {
						register_error(elgg_echo('event_manager:event:relationship:message:unavailable_relation'));
					}
				}
				
				if($notice){
					if($rsvp) {
						system_message(elgg_echo('event_manager:event:relationship:message:' . $rel));
					} else {
						register_error(elgg_echo('event_manager:event:relationship:message:error'));
					}
				}
			}
		} else {
			register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($guid, "Event")));
		}
	} else {
		register_error(elgg_echo("IOException:FailedToLoadGUID", array("Event", $guid)));
	}
	
	forward($forward_url);