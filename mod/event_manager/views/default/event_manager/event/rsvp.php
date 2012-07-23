<?php 

	$event = $vars["entity"];
	$event_relationship_options = event_manager_event_get_relationship_options();
	
	if(elgg_is_logged_in() && (elgg_get_logged_in_user_guid() != $event->owner_guid)) {
		if($event->openForRegistration()){
			$user_relation = $event->getRelationshipByUser();
				
			echo "<span class='event_manager_event_actions'>";
			if($user_relation) {
				echo "<b>" . elgg_echo("event_manager:event:rsvp") . "</b>";
			} else {
				echo elgg_echo("event_manager:event:rsvp");
			}
			echo "</span>";
			echo "<ul class='event_manager_event_actions_drop_down event_manager_event_select_relationship'>";
			foreach($event_relationship_options as $rel) {
				if(($rel == EVENT_MANAGER_RELATION_ATTENDING) || $event->$rel) {
					if($rel == EVENT_MANAGER_RELATION_ATTENDING) {
						if(!$event->hasEventSpotsLeft() && !$event->waiting_list_enabled) {
							continue;
						}
					}
					
					if($rel == $user_relation) {
						echo "<li class='selected'>" . elgg_echo('event_manager:event:relationship:' . $rel) . "</li>";
					} else {
						if($rel != EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST) {
							echo "<li>" . elgg_view("output/url", array("is_action" => true, "href" => "action/event_manager/event/rsvp?guid=" . $event->getGUID() . "&type=" . $rel, "text" => elgg_echo('event_manager:event:relationship:' . $rel))) . "</li>";
						}
					}
				}
			}
			
			if($user_relation) {
				echo "<li>" . elgg_view("output/url", array("is_action" => true, "href" => "action/event_manager/event/rsvp?guid=" . $event->getGUID() . "&type=" . EVENT_MANAGER_RELATION_UNDO, "text" => elgg_echo('event_manager:event:relationship:undo'))) . "</li>";
			}
			echo "</ul>";
		}
	}