<?php

	$day_guid = $vars["day_guid"];
	$slot_guid = $vars["slot_guid"];
	
	if($day_guid && ($entity = get_entity($day_guid)))
	{
		// assume new slot mode
		if(!($entity instanceof EventDay))
		{
			unset($entity);
		}
		
		$start_time_hours = '';
		$start_time_minutes = '';
		
		$end_time_hours = '';
		$end_time_minutes = '';
	} 
	elseif($slot_guid && ($entity = get_entity($slot_guid))) 
	{
		// assume slot edit mode
		if(!($entity instanceof EventSlot))
		{
			unset($entity);
		}
	}
	
	if($entity && $entity->canEdit())
	{
	
		if($entity instanceof EventSlot)
		{
			// assume slot edit mode
			$guid 			= $entity->getGUID();
			$title 			= $entity->title;
			$start_time		= $entity->start_time;	
			$end_time		= $entity->end_time;	
			$location		= $entity->location;
			$max_attendees	= $entity->max_attendees;
			$description	= $entity->description;			
		
			$start_time_hours = date('H', $entity->start_time);
			$start_time_minutes = date('i', $entity->start_time);	
		
			$end_time_hours = date('H', $entity->end_time);
			$end_time_minutes = date('i', $entity->end_time);			
			
			
			if($related_days = $entity->getEntitiesFromRelationship('event_day_slot_relation', false, 1))
			{
				$parent_guid = $related_days[0]->getGUID();
			}
		} 
		else 
		{
			// entity is a day
			$parent_guid	= $entity->getGUID();
			
		}
		
		$form_body .= elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
		$form_body .= elgg_view('input/hidden', array('name' => 'parent_guid', 'value' => $parent_guid));
		
		$form_body .= "<label>" . elgg_echo("title") . " *</label><br />";
		$form_body .= elgg_view('input/text', array('name' => 'title', 'value' => $title));
		
		$form_body .= "<label>" . elgg_echo("event_manager:edit:form:start_time") . " *</label><br />";
		
		$form_body .= event_manager_get_form_pulldown_hours('start_time_hours', $start_time_hours);
		$form_body .= event_manager_get_form_pulldown_minutes('start_time_minutes', $start_time_minutes).'<br />';
					
		$form_body .= "<label>" . elgg_echo("event_manager:edit:form:end_time") . " *</label><br />";
		
		$form_body .= event_manager_get_form_pulldown_hours('end_time_hours', $end_time_hours);
		$form_body .= event_manager_get_form_pulldown_minutes('end_time_minutes', $end_time_minutes).'<br />';

		$form_body .= "<label>" . elgg_echo("event_manager:edit:form:location") . "</label><br />";
		$form_body .= elgg_view('input/text', array('name' => 'location', 'value' => $location));

		$form_body .= "<label>" . elgg_echo("event_manager:edit:form:max_attendees") . "</label><br />";
		$form_body .= elgg_view('input/text', array('name' => 'max_attendees', 'value' => $max_attendees));

		$form_body .= "<label>" . elgg_echo("event_manager:edit:form:description") . "</label><br />";
		$form_body .= elgg_view('input/plaintext', array('name' => 'description', 'value' => $description));
					
		$form_body .= elgg_view('input/submit', array('value' => elgg_echo('submit')));
		
		$form = elgg_view('input/form', array(	'id' 	=> 'event_manager_form_program_slot', 
											'name' 	=> 'event_manager_form_program_slot', 
											'action' 		=> 'javascript:event_manager_program_add_slot($(\'#event_manager_form_program_slot\'))',
											'body' 			=> $form_body));
		
		echo elgg_view_module("main", elgg_echo("event_manager:form:program:slot"), $form, array("id" => "event-manager-program-slot-lightbox"));
	} else {
		echo elgg_echo("error");
	}