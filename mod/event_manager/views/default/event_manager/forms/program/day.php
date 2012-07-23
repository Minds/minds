<?php

	$event_guid = $vars["event_guid"];
	$day_guid = $vars["day_guid"];
	
	if($event_guid && ($entity = get_entity($event_guid))){
		// assume new day mode
		if(!($entity instanceof Event)){
			unset($entity);
		}
		
	} elseif($day_guid && ($entity = get_entity($day_guid))) {
		// assume day edit mode
		if(!($entity instanceof EventDay)){
			unset($entity);
		}
	}
	
	if($entity && $entity->canEdit()){
	
		if($entity instanceof EventDay){
			// assume day edit mode
			$guid 			= $entity->getGUID();
			$parent_guid	= $entity->owner_guid;	
			$title 			= $entity->title;
			$date 			= $entity->date;	
			if(!empty($date)){
				$date = date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $date);
			}		
		} else {
			// entity is a event
			$parent_guid	= $entity->getGUID();
			
			// make nice default date
			$days = $entity->getEventDays();
			$last_day = end($days);
			if(!$last_day){
				$date = ($entity->start_day+(3600*24));
			} else {
				$date = ($last_day->date+(3600*24));
			}
			
			$date = date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $date);
		}
		
		$form_body .= '<div">';
		
		$form_body .= elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
		$form_body .= elgg_view('input/hidden', array('name' => 'parent_guid', 'value' => $parent_guid));
		
		$form_body .= "<label>" . elgg_echo("title") . "</label><br />";
		$form_body .= elgg_view('input/text', array('name' => 'title', 'value' => $title));
		$form_body .= "<label>" . elgg_echo("event_manager:edit:form:start_day") . " *</label><br />";
		$form_body .= elgg_view('input/date', array('name' => 'date',  'id' => 'date',  'value' => $date)).'<br />';
		
		$form_body .= elgg_view('input/submit', array('value' => elgg_echo('submit')));
		$form_body .= '</div>';
		
		$body = elgg_view('input/form', array(	'id' 	=> 'event_manager_form_program_day', 
											'name' 	=> 'event_manager_form_program_day', 
											'action' 		=> 'javascript:event_manager_program_add_day($(\'#event_manager_form_program_day\'))',
											'body' 			=> $form_body));
		
		echo elgg_view_module('main', elgg_echo("event_manager:form:program:day"), $body, array("id" => "event-manager-program-day-lightbox"));
		
	} else {
		// TODO: nice error message
		echo elgg_echo("error");
	}