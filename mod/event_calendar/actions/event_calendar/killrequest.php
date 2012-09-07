<?php
// this action allows an admin or event owner to reject a calendar request

elgg_load_library('elgg:event_calendar');

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$event_guid = get_input('event_guid');

$user = get_entity($user_guid);
$event = get_entity($event_guid);

if (elgg_instanceof($event, 'object', 'event_calendar') 
	&& elgg_instanceof($user, 'user') 
	&& $event->canEdit() 
	&& check_entity_relationship($user_guid, 'event_calendar_request', $event_guid)) {
		
	remove_entity_relationship($user->guid, 'event_calendar_request', $event_guid);
	system_message(elgg_echo('event_calendar:requestkilled'));
} else {
	register_error(elgg_echo('event_calendar:review_requests:error:reject'));
}
	
forward(REFERER);
