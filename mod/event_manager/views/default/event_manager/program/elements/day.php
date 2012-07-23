<?php 

	$day = $vars["entity"];
	$participate = $vars['participate'];
	$register_type = $vars['register_type'];
	
	if(!empty($day) && ($day instanceof EventDay)){
		$can_edit = $day->canEdit();
		
		$details = $day->title;
		if($can_edit && !elgg_in_context('programmailview') && ($participate == false)) {
			$edit_day = "<a href='#' class='event_manager_program_day_edit' rel='" . $day->getGUID() . "'>" . elgg_echo("edit") . "</a>";
			$delete_day = "<a href='#' class='event_manager_program_day_delete'>" . elgg_echo("delete") . "</a>";
			
			$details .= " [ " . $edit_day . " | " . $delete_day . " ]";
		}
		
		if($vars["details_only"]){
			$result = $details;
		} else {
			$result = '<div class="event_manager_program_day"';
			if($vars["selected"]){
				$result .= ' style="display: block;"';
			}
			$result .= ' id="day_' . $day->getGUID() . '">';
			
			$result .= '<div class="event_manager_program_day_details" rel="' . $day->getGUID() . '">';
			
			$result .= $details;
			
			$result .= '</div>';
			
			if($daySlots = $day->getEventSlots()){
				foreach($daySlots as $slot){
					$result .= elgg_view("event_manager/program/elements/slot", array("entity" => $slot, 'participate' => $participate, 'register_type' => $register_type, "member" => $vars["member"]));							
				}
			}
			if($can_edit && !elgg_in_context('programmailview') && ($participate == false)){
				$result .= "<a href='#' class='elgg-button elgg-button-action event_manager_program_slot_add' rel='" . $day->getGUID() . "'>" . elgg_echo("event_manager:program:slot:add") . "</a>";
			}
			$result .= '</div>';
		}
		
		echo $result;
	}