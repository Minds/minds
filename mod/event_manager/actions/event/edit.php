<?php
 	
	$guid 					= get_input("guid");
	$container_guid			= get_input("container_guid");
	$title 					= get_input("title");
	$shortdescription 		= get_input("shortdescription");
	$tags			 		= get_input("tags");
	$organizer	 			= get_input("organizer");
	$description 			= get_input("description");
	$comments_on 			= get_input("comments_on");
	$location 				= get_input("location");
	$region 				= get_input("region");
	$event_type 			= get_input("event_type");
	$latitude 				= get_input("latitude");
	$longitude 				= get_input("longitude");
	$venue 					= get_input("venue");
	$start_day 				= get_input("start_day");
	$registration_ended		= get_input("registration_ended");
	$show_attendees			= get_input("show_attendees");
	$notify_onsignup		= get_input("notify_onsignup");
	$endregistration_day	= get_input("endregistration_day");
	$max_attendees			= get_input("max_attendees");
	$waiting_list			= get_input("waiting_list");
	$access_id 				= get_input("access_id");
	$with_program			= get_input("with_program");
	$delete_current_icon	= get_input("delete_current_icon");
	$registration_needed	= get_input("registration_needed");
	$register_nologin		= get_input("register_nologin");
	
	$event_interested		= get_input("event_interested");
	$event_presenting		= get_input("event_presenting");
	$event_exhibiting		= get_input("event_exhibiting");
	$event_organizing		= get_input("event_organizing");
	
	$waiting_list_enabled	= get_input("waiting_list_enabled");
	
	$start_time_hours = get_input("start_time_hours");
	$start_time_minutes = get_input("start_time_minutes");
	$start_time = mktime($start_time_hours, $start_time_minutes, 1, 0, 0, 0);
	
	$forward_url = REFERER;
	
	if(!empty($start_day)) {
		$date = explode('-',$start_day);
		$start_day = mktime(0,0,1,$date[1],$date[2],$date[0]);
	}

	if(!empty($endregistration_day)) {
		$date_endregistration_day = explode('-',$endregistration_day);
		$endregistration_day = mktime(0,0,1,$date_endregistration_day[1],$date_endregistration_day[2],$date_endregistration_day[0]);
	}		
	
	if(!empty($guid) && $entity = get_entity($guid)) {
		if($entity->getSubtype() == Event::SUBTYPE) {
			$event = $entity;
		}
	}
	
	if($event_type == '-') {
		$event_type = '';
	}
	
	if($region == '-') {
		$region = '';
	}
	
	if(!empty($tags)) {
		$tags = string_to_tag_array($tags);
	}
	
	if(!empty($max_attendees) && !is_numeric($max_attendees)) {
		$max_attendees = "";
	}
	
	if(!empty($title) && !empty($start_day)) {
		if(!$event)	{
			$newEvent = true;
			$event = new Event();
		}
		
		$event->title 				= $title;
		$event->description 		= $description;
		$event->container_guid 		= $container_guid;
		$event->access_id 			= $access_id;
		$event->save();
		
		$event->setLocation($location);
		$event->setLatLong($latitude, $longitude);
		$event->tags 				= $tags;
		
		
		if($newEvent) {
			$rsvp = $event->rsvp(EVENT_MANAGER_RELATION_ORGANIZING);
		}
		
		$event->shortdescription 	= $shortdescription;
		$event->comments_on 		= $comments_on;
		$event->registration_ended	= $registration_ended;
		$event->registration_needed	= $registration_needed;
		$event->show_attendees		= $show_attendees;
		$event->notify_onsignup		= $notify_onsignup;
		$event->max_attendees		= $max_attendees;
		$event->waiting_list		= $waiting_list;
		$event->venue 				= $venue;
		$event->region 				= $region;
		$event->event_type 			= $event_type;
		$event->organizer 			= $organizer;
		$event->start_day 			= $start_day;
		$event->start_time 			= $start_time;
		$event->end_time 			= $end_time;
		$event->with_program 		= $with_program;
		$event->endregistration_day = $endregistration_day;
		$event->register_nologin 	= $register_nologin;
		
		$event->event_interested 	= $event_interested;
		$event->event_presenting 	= $event_presenting;
		$event->event_exhibiting 	= $event_exhibiting;
		$event->event_organizing 	= $event_organizing;
		
		$event->waiting_list_enabled = $waiting_list_enabled;
				
		$eventDays = $event->getEventDays();
		if($with_program && !$eventDays) {
			$eventDay = new EventDay();
			$eventDay->title			= 'Event day 1';
			$eventDay->description		= 'Description';
			$eventDay->container_guid	= $event->getGUID();
			$eventDay->owner_guid		= $event->getGUID();
			$eventDay->access_id 		= $event->access_id;
			$eventDay->save();
			$eventDay->date				= $event->start_day;
			$eventDay->addRelationship($event->getGUID(), 'event_day_relation');
			
			$eventSlot = new EventSlot();
			$eventSlot->title			= 'Activity title';
			$eventSlot->description		= 'Activity description';
			$eventSlot->container_guid	= $event->container_guid;
			$eventSlot->owner_guid		= $event->owner_guid;
			$eventSlot->access_id 		= $event->access_id;
			$eventSlot->save();
			$eventSlot->location		= $event->location;
			$eventSlot->start_time		= '08:00';
			$eventSlot->end_time		= '09:00';
			$eventSlot->addRelationship($eventDay->getGUID(), 'event_day_slot_relation');
		}

		$event->setAccessToOwningObjects($access_id);
		
		$prefix = "events/".$event->guid."/";
		
		if ((isset($_FILES['icon'])) && (substr_count($_FILES['icon']['type'],'image/'))) {			
			$filehandler = new ElggFile();
			$filehandler->owner_guid = $event->owner_guid;
			$filehandler->setFilename($prefix . "master.jpg");
			$filehandler->open("write");
			$filehandler->write(get_uploaded_file('icon'));
			$filehandler->close();
		
			$thumbtiny 		= get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true);
			$thumbsmall 	= get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
			$thumbmedium 	= get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),100,100, true);
			$thumblarge 	= get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, true);
			
			if ($thumbtiny) {
				$thumb = new ElggFile();
				$thumb->owner_guid = $event->owner_guid;
				$thumb->setMimeType('image/jpeg');
		
				$thumb->setFilename($prefix."tiny.jpg");
				$thumb->open("write");
				$thumb->write($thumbtiny);
				$thumb->close();
		
				$thumb->setFilename($prefix."small.jpg");
				$thumb->open("write");
				$thumb->write($thumbsmall);
				$thumb->close();
		
				$thumb->setFilename($prefix."medium.jpg");
				$thumb->open("write");
				$thumb->write($thumbmedium);
				$thumb->close();
		
				$thumb->setFilename($prefix."large.jpg");
				$thumb->open("write");
				$thumb->write($thumblarge);
				$thumb->close();

				$event->icontime = time();
			}
		} elseif($delete_current_icon) {
			foreach(event_manager_icon_sizes() as $iconSize) {
				$filehandler = new ElggFile();
				$filehandler->owner_guid = $event->owner_guid;
				$filehandler->setFilename($prefix . $iconSize . ".jpg");
				
				if($filehandler->exists()) {
					$filehandler->delete();
				}
			}
			unset($event->icontime);
		}
		
		// added because we need an update event
		if($event->save()){
			system_message(elgg_echo("event_manager:action:event:edit:ok"));
			$forward_url = $event->getURL();
		} 
	} else {
		
		// TODO: replace with sticky forms functionality
		
		$_SESSION['createevent_values']['title'] 				= $title;
		$_SESSION['createevent_values']['shortdescription'] 	= $shortdescription;
		$_SESSION['createevent_values']['tags'] 				= $tags;
		$_SESSION['createevent_values']['description'] 			= $description;
		$_SESSION['createevent_values']['organizer'] 			= $organizer;
		$_SESSION['createevent_values']['comments_on'] 			= $comments_on;
		$_SESSION['createevent_values']['venue'] 				= $venue;
		$_SESSION['createevent_values']['location'] 			= $location;
		$_SESSION['createevent_values']['region'] 				= $region;
		$_SESSION['createevent_values']['event_type'] 			= $event_type;
		$_SESSION['createevent_values']['latitude'] 			= $latitude;
		$_SESSION['createevent_values']['longitude'] 			= $longitude;
		$_SESSION['createevent_values']['start_day'] 			= $start_day;
		$_SESSION['createevent_values']['start_time'] 			= $start_time;
		$_SESSION['createevent_values']['end_time'] 			= $end_time;
		$_SESSION['createevent_values']['endregistration_day'] 	= $endregistration_day;
		$_SESSION['createevent_values']['with_program']			= $with_program;
		$_SESSION['createevent_values']['registration_ended']	= $registration_ended;
		$_SESSION['createevent_values']['registration_needed']	= $registration_needed;
		$_SESSION['createevent_values']['register_nologin']		= $register_nologin;
		$_SESSION['createevent_values']['show_attendees']		= $show_attendees;
		$_SESSION['createevent_values']['notify_onsignup']		= $notify_onsignup;
		$_SESSION['createevent_values']['max_attendees']		= $max_attendees;
		$_SESSION['createevent_values']['waiting_list']			= $waiting_list;
		$_SESSION['createevent_values']['access_id'] 			= $access_id;
		
		$_SESSION['createevent_values']['event_interested'] 	= $event_interested;
		$_SESSION['createevent_values']['event_presenting'] 	= $event_presenting;
		$_SESSION['createevent_values']['event_exhibiting'] 	= $event_exhibiting;
		$_SESSION['createevent_values']['event_organizing'] 	= $event_organizing;
		
		register_error(elgg_echo("event_manager:action:event:edit:error_fields"));
	}
	
	forward($forward_url);
	