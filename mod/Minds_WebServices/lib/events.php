<?php
/**
 * Minds Web Services
 * Events
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
 
 
 /**
 * Web service to get a list of events
 *
 * @param string $context eg. own, friends or all (default all)
 * @param string $status open/closed etc.
 * @param int $limit  (optional) default 10
 * @param int $offset (optional) default 0
 * @param string $username (optional) the username of the user default loggedin user
 *
 * @return array $return Array of events
 */
function events_web_service_get_list($context, $status, $limit = 10, $offset = 0, $username) {
	
	$event_calendar_times = elgg_get_plugin_setting('times', 'event_calendar');
	$event_calendar_region_display = elgg_get_plugin_setting('region_display', 'event_calendar');
	$event_calendar_type_display = elgg_get_plugin_setting('type_display', 'event_calendar');
	$event_calendar_spots_display = elgg_get_plugin_setting('spots_display', 'event_calendar');
	$event_calendar_hide_end = elgg_get_plugin_setting('hide_end', 'event_calendar');
	$event_calendar_more_required = elgg_get_plugin_setting('more_required', 'event_calendar');
	$event_calendar_personal_manage = elgg_get_plugin_setting('personal_manage', 'event_calendar');
	
		
		if(!$username) {
			$user = elgg_get_logged_in_user_entity();
		} else {
			$user = get_user_by_username($username);
			if (!$user) {
				throw new InvalidParameterException('registration:usernamenotvalid');
			}
		}
		
		if($context == 'all'){
			$events = elgg_get_entities(array(
										'type' => 'object',
										'subtype' => 'event_calendar',
										'limit' => $limit,
										'full_view' => FALSE,
										));
		} elseif( $context == 'mine' || $context == 'user'){
			$events = elgg_get_entities(array(
										'type' => 'object',
										'subtype' => 'event_calendar',
										'owner_guid' => $user->guid,
										'limit' => $limit,
										'full_view' => FALSE,
										));
		} elseif( $context == 'friends'){
			$events = get_user_friends_objects($user->guid, 'event_calendar', $limit, $offset);
		}
		
		if($events){
			foreach($events as $single ) {
				$event['guid'] = $single->guid;
				$event['title'] = $single->title;
				$event['description'] = $single->description;
				$event['venue'] = $single->venue;
				$event['fee'] = $single->fee;
				$event['contact'] = $single->contact;
				$event['organiser'] = $single->organiser;
				
				if ($event_calendar_spots_display == 'yes') {
					$event['spots'] = $single->spots;
				}
				if ($event_calendar_region_display == 'yes') {
					$event['region'] = $single->region;
				}
				if ($event_calendar_type_display == 'yes') {
					$event['event_type'] = $single->event_type;
				}
				if ($event_calendar_personal_manage == 'by_event') {
					$event['personal_manage'] = $single->personal_manage;
				}
				
				$event['start_date'] = $single->start_date;
				$event['end_date'] = $single->end_date;
				$event['start_time'] = $single->start_time;
				$event['end_time'] = $single->end_time;
				$event['long_description'] = $single->long_description;
				$event['real_end_time'] = $single->real_end_time;
				
				$event['video_id'] = $single->kaltura_video_id;
				$event['thumbnail'] = $single->kaltura_video_thumbnail;
	
				$owner = get_entity($single->owner_guid);
				$event['owner']['guid'] = $owner->guid;
				$event['owner']['name'] = $owner->name;
				$event['owner']['username'] = $owner->username;
				$event['owner']['avatar_url'] = $owner->getIconUrl('small');
				
				$event['container_guid'] = $single->container_guid;
				$event['access_id'] = $single->access_id;
				$event['time_created'] = (int)$single->time_created;
				$event['time_updated'] = (int)$single->time_updated;
				$event['last_action'] = (int)$single->last_action;
				$return[] = $event;
			}
	
		} else {
			$msg = elgg_echo('event_calendar:none');
			throw new InvalidParameterException($msg);
		}
	
	return $return;
}

expose_function('events.get_list',
				"events_web_service_get_list",
				array(
						'context' => array ('type' => 'string', 'required' => false, 'default' => 'all'),
						'status' => array ('type' => 'string', 'required' => false, 'default' => 'all'),
					  	'limit' => array ('type' => 'int', 'required' => false, 'default' => 10),
					  	'offset' => array ('type' => 'int', 'required' => false, 'default' => 0),
					   	'username' => array ('type' => 'string', 'required' => false),
					),
				"Get list of event",
				'GET',
				false,
				false);

 
 /**
 * Web service to get a single event
 *
 * @param string $event_guid
 *
 * @return array $return Array of event info
 */
function events_web_service_get_event($event_guid) {
		
	$event_calendar_times = elgg_get_plugin_setting('times', 'event_calendar');
	$event_calendar_region_display = elgg_get_plugin_setting('region_display', 'event_calendar');
	$event_calendar_type_display = elgg_get_plugin_setting('type_display', 'event_calendar');
	$event_calendar_spots_display = elgg_get_plugin_setting('spots_display', 'event_calendar');
	$event_calendar_hide_end = elgg_get_plugin_setting('hide_end', 'event_calendar');
	$event_calendar_more_required = elgg_get_plugin_setting('more_required', 'event_calendar');
	$event_calendar_personal_manage = elgg_get_plugin_setting('personal_manage', 'event_calendar');
	
		
		$single = get_entity($event_guid);
		
		if($single){
				$event['guid'] = $single->guid;
				$event['title'] = $single->title;
				$event['description'] = $single->description;
				$event['venue'] = $single->venue;
				$event['fee'] = $single->fee;
				$event['contact'] = $single->contact;
				$event['organiser'] = $single->organiser;
				
				if ($event_calendar_spots_display == 'yes') {
					$event['spots'] = $single->spots;
				}
				if ($event_calendar_region_display == 'yes') {
					$event['region'] = $single->region;
				}
				if ($event_calendar_type_display == 'yes') {
					$event['event_type'] = $single->event_type;
				}
				if ($event_calendar_personal_manage == 'by_event') {
					$event['personal_manage'] = $single->personal_manage;
				}
				
				$event['start_date'] = $single->start_date;
				$event['end_date'] = $single->end_date;
				$event['start_time'] = $single->start_time;
				$event['end_time'] = $single->end_time;
				$event['long_description'] = $single->long_description;
				$event['real_end_time'] = $single->real_end_time;
				
				$event['video_id'] = $single->kaltura_video_id;
				$event['thumbnail'] = $single->kaltura_video_thumbnail;
	
				$owner = get_entity($single->owner_guid);
				$event['owner']['guid'] = $owner->guid;
				$event['owner']['name'] = $owner->name;
				$event['owner']['username'] = $owner->username;
				$event['owner']['avatar_url'] = $owner->getIconUrl('small');
				
				$event['container_guid'] = $single->container_guid;
				$event['access_id'] = $single->access_id;
				$event['time_created'] = (int)$single->time_created;
				$event['time_updated'] = (int)$single->time_updated;
				$event['last_action'] = (int)$single->last_action;
		} else {
			$msg = elgg_echo('event_calendar:none');
			throw new InvalidParameterException($msg);
		}
	
	return $event;
}

expose_function('events.get',
				"events_web_service_get_event",
				array(
						'event_guid' => array ('type' => 'int'),
					),
				"Get information about an event",
				'GET',
				false,
				false);

 /**
 * Web service to attend an event
 *
 * @param string $event_guid
 *
 * @return bool true/false
 */
 function events_web_service_attend_event($event_guid) {
	 
	elgg_load_library('elgg:event_calendar');

	$user = elgg_get_logged_in_user_entity();

 	$event = get_entity($event_guid);
	
	//get permission status... open or closed?
	if($event->personal_manage == "open"){
		if (!event_calendar_has_personal_event($event_guid,$user->getGuid())
		&& !event_calendar_has_collision($event_guid,$user->getGuid())) {
			if (!event_calendar_is_full($event_guid)) {
				add_entity_relationship($user->getGuid(),'personal_event',$event_guid);
				return TRUE;
			}
		}
	} else {
		 return event_calendar_send_event_request($event_guid, $user->getGuid());
	}
 
}
expose_function('events.attend',
				"events_web_service_attend_event",
				array(
						'event_guid' => array ('type' => 'int'),
					),
				"Attend an event",
				'GET',
				true,
				true);
				
 /**
 * Web service to cancel attending
 *
 * @param string $event_guid
 *
 * @return bool true/false
 */
 function events_web_service_cancel_event($event_guid) {
	 
	elgg_load_library('elgg:event_calendar');

	$user = elgg_get_logged_in_user_entity();

 	$event = get_entity($event_guid);
	
	return event_calendar_remove_personal_event($event_guid,$user->getGuid());
 
}
expose_function('events.cancel',
				"events_web_service_cancel_event",
				array(
						'event_guid' => array ('type' => 'int'),
					),
				"Cancel attending an event",
				'GET',
				true,
				true);

 /**
 * Web service to get list of attendees
 *
 * @param string $event_guid
 *
 * @return bool true/false
 */
 function events_web_service_attendees($event_guid, $limit, $offset) {
	
	return event_calendar_get_users_for_event($event_guid,$limit,$offset,false);
 
}
expose_function('events.attendees',
				"events_web_service_attendees",
				array(
						'event_guid' => array ('type' => 'int'),
						'limit' => array('type'=>'int'),
						'offset' => array('type'=>'int')
					),
				"List of event atendees",
				'GET',
				false,
				false);


//@TODO: THESE MAY NOT WORK -- NEEDS TESTING			
 /**
 * Web service to create an event
 *
 * @param string $event_guid
 *
 * @return array $return guid of event
 */
function events_web_service_create_event() {
	
	return event_calendar_set_event_from_form();
	
}
expose_function('events.create',
				"events_web_service_get_event",
				array(
						
					),
				"Add an event",
				'GET',
				true,
				true);
				
 /**
 * Web service to delete an event
 *
 * @param string $event_guid
 *
 * @return boo true/false
 */
 function events_web_service_delete_event($event_guid) {

 	$event = get_entity($event_guid);
	
	if (elgg_instanceof($event,'object','event_calendar') && $event->canEdit()) {
	
		$container = get_entity($event->container_guid);
		$event->delete();
		return elgg_echo('event_calendar:delete_response');

	} else {
		$msg = elgg_echo('event_calendar:error_delete');
		throw new InvalidParameterException($msg);
	}
 
}
expose_function('events.delete',
				"events_web_service_delete_event",
				array(
						'event_guid' => array ('type' => 'int'),
					),
				"Delete an event",
				'GET',
				true,
				true);