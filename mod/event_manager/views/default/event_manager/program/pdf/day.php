<?php 

	$day = $vars["entity"];
	$participate = $vars['participate'];
	$register_type = $vars['register_type'];
	
	if(!empty($day) && ($day instanceof EventDay))
	{
		$result = '<div class="event_manager_program_day" style="display: block; padding-bottom: 75px;">';
		
		$result .= '<div class="event_manager_program_day_details">';
		
		$result .= $day->title .' ('.date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $day->date).')';
		
		$result .= '</div>';
		
		if($daySlots = $day->getEventSlots())
		{
			foreach($daySlots as $slot)
			{
				$result .= elgg_view("event_manager/program/pdf/slot", array("entity" => $slot, 'participate' => $participate, 'register_type' => $register_type));							
			}
		}
		$result .= '</div>';
		
		echo $result;
	}