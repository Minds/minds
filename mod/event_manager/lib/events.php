<?php

	function event_manager_update_object_handler($event, $type, $object){
		
		if(!empty($object) && ($object instanceof Event)){
			$fillup = false;
			
			if($object->with_program && $object->hasSlotSpotsLeft()){
				$fillup = true;
			} elseif (!$oject->with_program && $object->hasEventSpotsLeft()){
				$fillup = true;
			}
			
			if($fillup){
				while($object->generateNewAttendee()){
					continue;
				}
			}
		}
	}