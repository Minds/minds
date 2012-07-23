<?php 

	$event_guid = get_input("event_guid");
	$day_guid = get_input("day_guid");
	
	echo elgg_view("event_manager/forms/program/day", array("day_guid" => $day_guid, "event_guid" => $event_guid));
	
	exit();