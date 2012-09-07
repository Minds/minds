<?php
$event_guid = get_input('event_guid');
$event = get_entity($event_guid);
$event_calendar_add_users = elgg_get_plugin_setting('add_users', 'event_calendar');
if (($event_calendar_add_users == 'yes') && elgg_instanceof($event,'object','event_calendar') && $event->canEdit()) {
	$members = get_input('members');
	// clear the event from all personal calendars
	remove_entity_relationships($event_guid, 'personal_event', TRUE);
	// add event to personal calendars
	$event_calendar_add_users_notify = elgg_get_plugin_setting('add_users_notify', 'event_calendar');
	$site_guid = elgg_get_site_entity()->guid;
	foreach ($members as $user_guid) {
		add_entity_relationship($user_guid,'personal_event',$event_guid);
		if ($event_calendar_add_users_notify == 'yes') {
			$subject = elgg_echo('event_calendar:add_users_notify:subject');
			$user = get_user($user_guid);
			$message = elgg_echo('event_calendar:add_users_notify:body',array($user->name,$event->title,$event->getURL()));
			notify_user($user_guid, $site_guid, $subject, $message, NULL, 'email');
		}
	}
	system_message(elgg_echo('event_calendar:manage_subscribers:success'));
	forward($event->getURL());
} else {
	register_error(elgg_echo('event_calendar:manage_subscribers:error'));
	forward();
}
