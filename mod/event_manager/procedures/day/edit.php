<?php 

	$result = array();
	$parent_guid = get_input('parent_guid');
	
	$result['valid'] = 0;
	
	if(!empty($parent_guid) && $event = get_entity($parent_guid)){
		
		if(($event->getSubtype() == Event::SUBTYPE) && ($event->canEdit()))	{
			$guid = get_input("guid");
			$title = get_input("title");
			$date = get_input("date");
		
			if(!empty($date)){
				$date_parts = explode('-',$date);
				$date = mktime(0,0,1,$date_parts[1],$date_parts[2],$date_parts[0]);
			}
			
			if($guid && $day = get_entity($guid)){
				// edit existing
				if(!($day instanceof EventDay)){
					unset($day);
				}
				$edit = true;
			} else {
				// create new
				$day = new EventDay();
			}
			if($day && !empty($date)){
				$day->title				= $title;
				$day->container_guid	= $event->getGUID();
				$day->owner_guid		= $event->getGUID();
				$day->access_id			= $event->access_id;
				if($day->save()){
					
					$day->date = $date;
					
					$day->addRelationship($event->getGUID(), 'event_day_relation');
					
					$result['valid'] = 1;
					$result['guid'] = $day->getGUID();

					if($edit){
						$content_title = date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $day->date);
						
						$content_body = elgg_view("event_manager/program/elements/day", array("entity" => $day, "details_only" => true));
						
						$result['edit'] = 1;
					} else {
						
						$content_title = '<li><a rel="day_' . $day->getGUID() . '" href="javascript:void(0);">' . date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $day->date) .'</a></li>';
						
						$content_body = elgg_view("event_manager/program/elements/day", array("entity" => $day));
						$result['edit'] = 0;
					}
					
					$result['content_title'] = $content_title;
					$result['content_body'] = $content_body;
				}
			}
		}
	}
	
	echo json_encode($result);
	exit;