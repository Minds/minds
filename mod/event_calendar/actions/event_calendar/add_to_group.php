<?php
$event_id = get_input("event_id",0);
$group_id = get_input("group_id",0);
$event = get_entity($event_id);
$group = get_entity($group_id);
if ($group && $group->canEdit()) {
	add_entity_relationship($event_id, "display_on_group", $group_id );
	system_message(elgg_echo('event_calendar:add_to_group:success'));
}
forward($event->getUrl());
