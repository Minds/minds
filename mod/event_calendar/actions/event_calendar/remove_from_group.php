<?php
$event_id = get_input("event_id",0);
$group_id = get_input("group_id",0);
$event = get_entity($event_id);
remove_entity_relationship($event_id, "display_on_group", $group_id );
system_message(elgg_echo('event_calendar:remove_from_group:success'));
forward($event->getUrl());
?>