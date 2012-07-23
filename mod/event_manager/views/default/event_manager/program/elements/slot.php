<?php 

	$slot = $vars["entity"];
	$participate = $vars["participate"];
	$register_type = $vars["register_type"];
	
	if(!empty($slot) && ($slot instanceof EventSlot)) {
		$result = "<table id='" . $slot->getGUID() . "'>";
	
		$result .= "<tr><td rowspan='2' class='event_manager_program_slot_attending'>";
		
		if(elgg_is_logged_in() && ($user_guid = elgg_get_logged_in_user_guid())) {
			if(check_entity_relationship($user_guid, EVENT_MANAGER_RELATION_SLOT_REGISTRATION, $slot->getGUID())) {
				if(!$participate) {
					$registered_for_slot = '<div title="' . elgg_echo("event_manager:event:relationship:event_attending") . '" class="event_manager_program_slot_attending_user"></div>';
				} else {
					$registered_for_slot = elgg_view('input/checkbox', array('value' => '1', 'name' => 'slotguid_'.$slot->getGUID(), 'id' => 'slotguid_'.$slot->getGUID(), 'class' => 'event_manager_program_participatetoslot', "checked" => "checked"));
				}
			} else {
				if($participate &&  ($slot->hasSpotsLeft() || $register_type == 'waitinglist')) {
					$registered_for_slot = elgg_view('input/checkbox', array('name' => 'slotguid_'.$slot->getGUID(), 'id' => 'slotguid_'.$slot->getGUID(), 'class' => 'event_manager_program_participatetoslot', 'value' => '1'));
				}
			}
		} else {
			if($participate) {
				$registered_for_slot = elgg_view('input/checkbox', array('name' => 'slotguid_'.$slot->getGUID(), 'id' => 'slotguid_'.$slot->getGUID(), 'class' => 'event_manager_program_participatetoslot', 'value' => '1'));
			} elseif(!empty($vars["member"]) && check_entity_relationship($vars["member"], EVENT_MANAGER_RELATION_SLOT_REGISTRATION, $slot->getGUID())){
				$registered_for_slot = '<div title="' . elgg_echo("event_manager:event:relationship:event_attending") . '" class="event_manager_program_slot_attending_user"></div>';
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
		$result .= date('H', $start_time) . ":" . date('i', $start_time) . " - " . date('H', $end_time) . ":" . date('i', $end_time);
		$result .= "</td><td class='event_manager_program_slot_details' rel='" . $slot->getGUID() . "'>";
		$result .= "<span><b>" . $slot->title . "</b></span>";
		
		if($slot->canEdit() && !elgg_in_context('programmailview') && ($participate == false)) {
			$edit_slot = "<a href='#' class='event_manager_program_slot_edit' rel='" . $slot->getGUID() . "'>" . elgg_echo("edit") . "</a>";
			$delete_slot = "<a href='#' class='event_manager_program_slot_delete'>" . elgg_echo("delete") . "</a>";
			
			$result .= " [ " . $edit_slot . " | " . $delete_slot . " ]";
		}
		
		$subtitle_data = array();
		if($location = $slot->location) {
			$subtitle_data[] = $location;
		}
		
		if(!empty($slot->max_attendees)) {
			if(($slot->max_attendees > 0) && (($slot->max_attendees - $slot->countRegistrations()) > 0)) {
				$subtitle_data[] = ($slot->max_attendees - $slot->countRegistrations()) . ' ' . strtolower(elgg_echo('event_manager:edit:form:spots_left'));
			} else {
				$subtitle_data[] = strtolower(elgg_echo('event_manager:edit:form:spots_left:full'));
				
				$event = $slot->getEvent();
				if($event->waiting_list_enabled && $slot->getWaitingUsers(true)>0) {
					$subtitle_data[] = $slot->getWaitingUsers(true).elgg_echo('event_manager:edit:form:spots_left:waiting_list');
				} 
			}
		}
		
		if(!empty($subtitle_data)) {
			$result .= "<div class='elgg-quiet'>" . implode(" - ", $subtitle_data) . "</div>";
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
