<?php

set_time_limit(0); // make sure we do not run out of time

$migratable_events = event_manager_get_migratable_events();

if($migratable_events['count'] > 0)
{
	$i = 0;
	foreach($migratable_events['entities'] as $old_event)
	{
		$new_event = new Event();
		$new_event->title 				= $old_event->title;
		$new_event->description 		= $old_event->long_description.
											(($old_event->fees)?'<p>Fees: '.$old_event->fees.'</p>':'').
											(($old_event->contact)?'<p>Contact: '.$old_event->contact.'</p>':'').
											(($old_event->start_time)?'<p>Start time: '.	str_pad((int)($old_event->start_time / 60), 2, "0", STR_PAD_LEFT).' : '.str_pad((int)($old_event->start_time % 60), 2, "0", STR_PAD_LEFT).'</p>':'').
											(($old_event->end_time)?'<p>End time: '.	str_pad((int)($old_event->end_time / 60), 2, "0", STR_PAD_LEFT).' : '.str_pad((int)($old_event->end_time % 60), 2, "0", STR_PAD_LEFT).'</p>':'');
		
		
		$new_event->owner_guid 			= $old_event->owner_guid;
		$new_event->container_guid 		= $old_event->container_guid;
		$new_event->site_guid 			= $old_event->site_guid;
		$new_event->access_id 			= $old_event->access_id;
		$new_event->save();
		
		// anti date (need to be after first save)
		$new_event->time_created 		= $old_event->time_created;
		
		$new_event->tags 				= $old_event->event_tags;
		
		$new_event->venue 				= $old_event->venue;
		$new_event->start_day 			= $old_event->start_date;
		$new_event->end_day 			= $old_event->end_date;
		$new_event->region 				= (($old_event->region != '-')?$old_event->region:'');
		$new_event->event_type 			= (($old_event->event_type != '-')?$old_event->event_type:'');
		$new_event->shortdescription 	= $old_event->description;
		$new_event->organizer 			= $old_event->organiser;
		
		// set correct default behaviour
		$new_event->show_attendees		= true;
		$new_event->comments_on			= true;
				
		if($annotations = $old_event->getAnnotations('personal_event'))
		{
			foreach($annotations as $annotation)
			{
				$new_event->addRelationship($annotation->value, EVENT_MANAGER_RELATION_ATTENDING);
			}
		}
		
		$old_event->migrated = 1;
		$old_event->save();
		
		$i++;
	}
	
	system_message(sprintf(elgg_echo("event_manager:settings:migration:success"), $i));
}
else 
{
	register_error(elgg_echo('event_manager:settings:migration:noeventstomigrate'));
}

forward(REFERER);