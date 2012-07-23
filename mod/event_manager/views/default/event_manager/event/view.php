<?php 
	
	$event = $vars["entity"];
	$owner = $event->getOwnerEntity();
	$output = '';
	
	$actions = elgg_view("event_manager/event/actions", $vars);	
	
	if($event->icontime){
		$output .= '<div class="event_manager_event_view_image"><a href="' . $event->getIcon('master') . '" target="_blank"><img src="' . $event->getIcon('medium') . '" border="0" /></a></div>';
	}
	
	$output .= '<div class="event_manager_event_view_owner">'.elgg_echo('event_manager:event:view:createdby') . '</span> <a class="user" href="' . $owner->getURL().'">' . $owner->name . '</a> ' . elgg_view_friendly_time($event->time_created) . '</div>';
	
	// event details
	$event_details = "<table>";
	if($location = $event->getLocation()){
		$event_details .= '<tr><td class="event-manager-event-details-labels"><b>' . elgg_echo('event_manager:edit:form:location') . '</b>:</td><td>';
		$event_details .= '<a href="' . EVENT_MANAGER_BASEURL . '/event/route?from=' . $event->getLocation() . '" class="openRouteToEvent">' . $event->getLocation() . '</a>';
		$event_details .= '</td></tr>';
	}
	
	$event_details .= '<tr><td class="event-manager-event-details-labels"><b>' . elgg_echo('event_manager:edit:form:start_day') . '</b>:</td><td>' . date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $event->start_day) . '</td></tr>';
	
	if(!$event->with_program){
		$event_details .= '<tr><td class="event-manager-event-details-labels"><b>' . elgg_echo('event_manager:edit:form:start_time') . '</b>:</td><td>' . date('H', $event->start_time) . ':' . date('i', $event->start_time) . '</td></tr>';
	}
	
	// optional end day
	if($organizer = $event->organizer){
		$event_details .= '<tr><td class="event-manager-event-details-labels"><b>' . elgg_echo('event_manager:edit:form:organizer') . '</b>:</td><td>' . $organizer . '</td></tr>';
	}
	
	if($max_attendees = $event->max_attendees){
		$event_details .= '<tr><td class="event-manager-event-details-labels"><b>' . elgg_echo('event_manager:edit:form:spots_left') . '</b>:</td><td>';
		
		$spots_left = ($max_attendees - $event->countAttendees());
		if($spots_left < 1) {
			$count_waitinglist = $event->countWaiters();
			if($count_waitinglist > 0){
				$event_details .= elgg_echo('event_manager:full') . ', ' . $count_waitinglist . ' ';
				if($count_waitinglist == 1) {
					$event_details .= elgg_echo('event_manager:personwaitinglist');
				} else {
					$event_details .= elgg_echo('event_manager:peoplewaitinglist');
				}
			} else {
				$event_details .= elgg_echo('event_manager:full');
			}
		} else {
			$event_details .= $spots_left;
		}
		
		$event_details .= '</td></tr>';
	}
	
	if($description = $event->description){
		$event_details .= '<tr><td class="event-manager-event-details-labels"><b>' . elgg_echo('event_manager:edit:form:description') . '</b>:</td><td>' . $description . '</td></tr>';
	}
	
	if($region = $event->region){
		$event_details .= '<tr><td class="event-manager-event-details-labels"><b>' . elgg_echo('event_manager:edit:form:region') . '</b>:</td><td>' . $region . '</td></tr>';
	}
	
	if($type = $event->event_type){
		$event_details .= '<tr><td class="event-manager-event-details-labels"><b>' . elgg_echo('event_manager:edit:form:type') . '</b>:</td><td>' . $type . '</td></tr>';
	}
	
	if($files = $event->hasFiles()){
		$user_path = 'events/' . $event->getGUID() . '/files/';
		
		$event_details .= '<tr><td class="event-manager-event-details-labels"><b>' . elgg_echo('event_manager:edit:form:files') . '</b>:</td><td>';
		$event_details .= "<div class='event-manager-event-files'>";
		foreach($files as $file){
			$event_details .= '<a href="' . EVENT_MANAGER_BASEURL . '/event/file/' . $event->getGUID() . '/' . $file->file . '">' . elgg_view_icon("download") . " " . $file->title . '</a><br />';
		}
		
		$event_details .= '</div>';
		$event_details .= '</ul></td></tr>';
	}
	
	$event_details .= "</table>";
	
	$output .= $event_details;
	
	$output .= '<div class="clearfloat"></div>';
	$output .= $actions;
		
	if($event->show_attendees){
		$output .= elgg_view("event_manager/event/attendees", $vars);
	}
	
	if($event->with_program){
		$output .= elgg_view("event_manager/program/view", $vars);
	}
	
	echo elgg_view_module("main", "", $output);
	
	if($event->comments_on){	
		echo elgg_view_comments($event);
	}