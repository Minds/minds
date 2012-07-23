<?php 

	$slot = $vars["entity"];
	$participate = $vars["participate"];
	$register_type = $vars["register_type"];
	
	if(!empty($slot) && ($slot instanceof EventSlot))
	{
		$result = "<table id='" . $slot->getGUID() . "'>";
	
		$result .= "<tr><td rowspan='2' class='event_manager_program_slot_attending'>";
		
		
		if(elgg_is_logged_in() && ($user_guid = elgg_get_logged_in_user_guid()))
		{
			if(check_entity_relationship($user_guid, EVENT_MANAGER_RELATION_SLOT_REGISTRATION, $slot->getGUID()))
			{
				$registered_for_slot = '<div title="' . elgg_echo("event_manager:event:relationship:event_attending") . '" class="event_manager_program_slot_attending_user_pdf"></div>';
			}
		}
		
		if($registered_for_slot){
			$result .= $registered_for_slot;
		} else {
			$result .= "&nbsp;";
		}
		
		$start_time = $slot->start_time;
		$end_time = $slot->end_time;
		
		$result .= "</td><td class='event_manager_program_slot_time'>";
			$result .= date('H',$start_time) . ":" . date('i',$start_time) . " - " . date('H',$end_time) . ":" . date('i',$end_time);
		$result .= "</td><td class='event_manager_program_slot_details' rel='" . $slot->getGUID() . "'>";
		$result .= "<span class='event_manager_program_slot_title'>" . $slot->title . "</span>";
		
		$subtitle_data = array();
		if($location = $slot->location)
		{
			$subtitle_data[] = $location;
		}
		
		if(!empty($subtitle_data))
		{
			$result .= "<div class='event_manager_program_slot_subtitle'>" . implode(" - ", $subtitle_data) . "</div>";
		}
		
		$result .= "</td></tr>";
		
		$result .= "<tr><td>";
		$result .= "&nbsp;";
		$result .= "</td><td>";
		$result .= "<div class='event_manager_program_slot_description'>" . elgg_view("output/text", array("value" => $slot->description)) . "</div>";
		
		$result .= "</td></tr>";
		
		$result .= "</table>";
		
		echo $result;
	}
	