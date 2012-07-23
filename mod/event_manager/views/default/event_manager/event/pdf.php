<?php 
	
	$event = $vars["entity"];
	$owner = $event->getOwnerEntity();
	
	if($event->icontime){
		$output .= '<div class="event_manager_event_view_image"><img src="'.$event->getIcon('medium').'" border="0" /></div>';
	}
	
	$output .= '<div class="event_manager_event_view_owner">'.elgg_echo('event_manager:event:view:createdby').'</span> <a class="user" href="'.$owner->getURL().'">'.$owner->name.'</a> '.date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $event->time_created).'</div>';
	
	// event details
	$event_details = "<table>";
	if($location = $event->getLocation()){
		$event_details .= '<tr><td><b>'.elgg_echo('event_manager:edit:form:location').'</b></td><td>: ';
		$event_details .= $event->getLocation();
		$event_details .= '</td></tr>';
	}
	
	$event_details .= '<tr><td><b>'.elgg_echo('event_manager:edit:form:start_day').'</b></td><td>: '.date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $event->start_day).'</td></tr>';
	
	if($organizer = $event->organizer){
		$event_details .= '<tr><td><b>'.elgg_echo('event_manager:edit:form:organizer').'</b></td><td>: '.$organizer.'</td></tr>';
	}
	
	if($description = $event->description){
		$event_details .= '<tr><td><b>'.elgg_echo('event_manager:edit:form:description').'</b></td><td>: '. $description .'</td></tr>';
	}
	
	if($region = $event->region){
		$event_details .= '<tr><td><b>'.elgg_echo('event_manager:edit:form:region').'</b></td><td>: '.$region.'</td></tr>';
	}
	
	if($type = $event->event_type){
		$event_details .= '<tr><td><b>'.elgg_echo('event_manager:edit:form:type').'</b></td><td>: '.$type.'</td></tr>';
	}
	
	$event_details .= "</table>";
	
	$output .= $event_details;
		
	echo $output;