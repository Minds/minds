<?php
// asks the event owner to add you to the event
elgg_load_library('elgg:event_calendar');

$event_guid = get_input('guid',0);
$user_guid = elgg_get_logged_in_user_guid();
$event = get_entity($event_guid);
if (elgg_instanceof($event,'object','event_calendar')) {
	if (event_calendar_send_event_request($event,$user_guid)) {
		system_message(elgg_echo('event_calendar:request_event_response'));
	} else {
		register_error(elgg_echo('event_calendar:request_event_error'));
	}
}

forward(REFERER);
