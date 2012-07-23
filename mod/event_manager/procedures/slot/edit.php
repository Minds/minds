<?php 

	$result = array();
	$parent_guid = (int) get_input('parent_guid');
	
	$result['valid'] = 0;
	$result['edit'] = 0;
	
	if(!empty($parent_guid) && $day = get_entity($parent_guid)){
		
		if(($day->getSubtype() == EventDay::SUBTYPE) && $day->canEdit()) {
	
			$guid = get_input("guid");
			
			$title = get_input("title");
			$description = get_input("description");
			
			$start_time_hours = get_input("start_time_hours");
			$start_time_minutes = get_input("start_time_minutes");
			$start_time = mktime($start_time_hours, $start_time_minutes, 1, 0, 0, 0);
			
			$end_time_hours = get_input("end_time_hours");
			$end_time_minutes = get_input("end_time_minutes");
			$end_time = mktime($end_time_hours, $end_time_minutes, 1, 0, 0, 0);
			
			$location = get_input("location");
			$max_attendees = get_input("max_attendees");
			
			if(!empty($title) && !empty($start_time) && !empty($end_time)){
				if($guid && $slot = get_entity($guid)){
					// edit existing
					if(!($slot instanceof EventSlot)){
						
						unset($slot);
					}
					$result['edit'] = 1;
				} else {
					// create new
					$slot = new EventSlot();
				}
				
				if($slot) {
					$slot->title 			= $title;
					$slot->description 		= $description;
					$slot->container_guid	= $day->container_guid;
					$slot->owner_guid		= $day->owner_guid;
					$slot->access_id		= $day->access_id;
					
					if($slot->save()){

						// add metadata
						$slot->start_time = $start_time;
						$slot->end_time = $end_time;
						$slot->location = $location;
						$slot->max_attendees = $max_attendees;
						
						$slot->addRelationship($day->getGUID(), 'event_day_slot_relation');
						
						$result['valid'] = 1;
						$result['guid'] = $slot->getGUID();
						$result['parent_guid'] = $parent_guid;
						$result['content'] = elgg_view('event_manager/program/elements/slot', array('entity' => $slot));
					}
				}
			}
		}
	}
	
	echo json_encode($result);
	exit;