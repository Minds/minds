<?php
elgg_load_library('elgg:event_calendar');

$event_guid = get_input('guid',0);
$event = get_entity($event_guid);
if (elgg_instanceof($event,'object','event_calendar')) {
	$user_guid = elgg_get_logged_in_user_guid();
	if (!event_calendar_has_personal_event($event_guid,$user_guid)) {
		if (event_calendar_add_personal_event($event_guid,$user_guid)) {
			system_message(elgg_echo('event_calendar:add_to_my_calendar_response'));
		} else {
			register_error(elgg_echo('event_calendar:add_to_my_calendar_error'));
		}
	}
}

forward(REFERER);
