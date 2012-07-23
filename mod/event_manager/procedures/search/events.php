<?php

	$search = get_input('search');
	$advanced_search = get_input('advanced_search');
	
	$search_type = get_input('search_type');
	
	$offset = (int) get_input('offset', 0);
	$container_guid = get_input('container_guid');
	
	$type = get_input('type');
	
	$past_events = get_input('past_events');
	$attending = get_input('attending');
	$owning = get_input('owning');
	$friendsattending = get_input('friendsattending');
	
	$region = get_input('region');
	$event_type = get_input('event_type');
	
	$start_day = get_input('start_day');
	$end_day = get_input('end_day');
	
	$returnData['valid'] = 0;
	
	if($advanced_search) {
		$options['advanced'] = true;
		
		if($attending) {
			$options['meattending'] = true;
		}
		
		if($owning) {
			$options['owning'] = true;
		}
		
		if($friendsattending) {
			$options['friendsattending'] = true;
		}
		
		if($region != '-') {
			$options['region'] = $region;
		}
		
		if($event_type != '-') {
			$options['event_type'] = $event_type;
		}
		
		if(!empty($start_day)) {
			$start_day = explode('-',$start_day);		
			$start_day_ts 	= mktime(0,	0,	1,	$start_day[1],	$start_day[2],	$start_day[0]);				
			$options['start_day'] = $start_day_ts;
		}
		
		if(!empty($end_day)) {
			$end_day = explode('-',$end_day);			
			$end_day_ts 	= mktime(23,59,	59,	$end_day[1], $end_day[2], $end_day[0]);			
			$options['end_day'] = $end_day_ts;
		}
		
		if(empty($end_day) && empty($start_day) && empty($search)){	
			$options['past_events'] = false;
		} else {
			$options['past_events'] = true;
		}
	} else {
		if($past_events) {
			$options['past_events'] = true;
		}
	}
	
	if(!empty($container_guid)) {
		$options["container_guid"] = $container_guid;
	}
	
	$options['query'] = $search;
	$options['offset'] = $offset;
	
	if($search_type == 'list') {
		$options['limit'] = EVENT_MANAGER_SEARCH_LIST_LIMIT;
		$entities = event_manager_search_events($options);
		
		$returnData['content'] = elgg_view_entity_list($entities['entities'], $entities['count'], $offset, EVENT_MANAGER_SEARCH_LIST_LIMIT, false, false, false);
		
		if(($entities['count'] - ($offset + EVENT_MANAGER_SEARCH_LIST_LIMIT)) > 0) {
			$returnData['content'] .= '<div id="event_manager_event_list_search_more" rel="'.($offset+EVENT_MANAGER_SEARCH_LIST_LIMIT).'">'.elgg_echo('event_manager:list:showmorevents').' ('.($entities['count']-($offset+EVENT_MANAGER_SEARCH_LIST_LIMIT)).')</div>';
		}
		
		if($entities['count'] < 1) {
			$returnData['content'] .= elgg_echo('event_manager:list:noresults');
		}
	} else {
		$options['limit'] = EVENT_MANAGER_SEARCH_LIST_MAPS_LIMIT;
		$entities = event_manager_search_events($options);
		foreach($entities['entities'] as $event) {
			elgg_push_context("maps");
			
			$eventBox = elgg_view_entity($event);
			
			elgg_pop_context();
			
			if($event->location) {
				$returnData['markers'][] = array(	'lat' => $event->getLatitude(), 
													'lng' => $event->getLongitude(), 
													'title' => $event->title, 
													'html' => $eventBox,
													'hasrelation' => $event->getRelationshipByUser(),
													'iscreator' => (($event->getOwner() == elgg_get_logged_in_user_guid())?'owner':null)
													);
			}
		}
	}
	
	$returnData['count'] = $entities['count'];
	
	$returnData['valid'] = 1;
	
	$returnData['offset'] = $offset;
	
	echo json_encode($returnData);
	
	exit;