<?php
// this action allows an admin or event owner to approve a calendar request

elgg_load_library('elgg:event_calendar');

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$event_guid = get_input('event_guid');

$user = get_entity($user_guid);
$event = get_entity($event_guid);

if (elgg_instanceof($event, 'object', 'event_calendar') 
	&& elgg_instanceof($user, 'user') 
	&& $event->canEdit() 
	&& check_entity_relationship($user_guid, 'event_calendar_request', $event_guid))	{
		
	if (event_calendar_add_personal_event($event_guid,$user_guid)) {
		remove_entity_relationship($user_guid, 'event_calendar_request', $event_guid);
		notify_user($user_guid, $CONFIG->site->guid, elgg_echo('event_calendar:add_users_notify:subject'),
							sprintf(
							elgg_echo('event_calendar:add_users_notify:body'),
							$user->name,
							$event->title,
							$event->getURL()
							)
		);
		system_message(elgg_echo('event_calendar:request_approved'));		
	}
} else {
	register_error(elgg_echo('event_calendar:review_requests:error:approve'));
}
	
forward(REFERER);
