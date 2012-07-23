<?php 

	$event = $vars["entity"];
	if(!empty($event) && ($event instanceof Event))
	{
		if($event->with_program)
		{
			if($eventDays = $event->getEventDays())
			{
				foreach($eventDays as $key => $day)
				{					
					$content .= elgg_view("event_manager/program/pdf/day", array("entity" => $day, "selected" => $selected));					
				}
			}
			
			echo "<h3>" . elgg_echo('event_manager:event:progam') . "</h3>";
			
			echo $content;
		}
	}	