<?php 

	$day_guid = get_input("day_guid");
	$slot_guid = get_input("slot_guid");
	
	echo elgg_view("event_manager/forms/program/slot", array("day_guid" => $day_guid, "slot_guid" => $slot_guid));
	
	exit();