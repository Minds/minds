<?php
elgg_load_library('elgg:event_calendar');
$event_guid = get_input("event_guid",0);
$event = get_entity($event_guid);
$group = get_entity($event->container_guid);
if (elgg_instanceof($group,'group') && elgg_instanceof($event,'object','event_calendar') && $group->canEdit()) {
	$members = $group->getMembers(0,0);
	foreach($members as $member) {
		event_calendar_remove_personal_event($event->guid,$member->guid);
	}
	system_message(elgg_echo('event_calendar:remove_from_group_members:success'));
} else {
	register_error(elgg_echo('event_calendar:remove_from_group_members:error'));
}
forward("event_calendar/manage_users/$event_guid");
