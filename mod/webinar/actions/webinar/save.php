<?php

elgg_load_library('elgg:webinar');

$variables = webinar_prepare_form_vars();
$input = array();
foreach (array_keys($variables) as $field) {
	$input[$field] = get_input($field);
	if ($field == 'title') {
		$input[$field] = strip_tags($input[$field]);
	}
}

elgg_make_sticky_form('webinar');

if (!$input['title']) {
	register_error(elgg_echo('webinar:error:no_title'));
	forward(REFERER);
}


if (sizeof($input) > 0) {
	
	if(isset($input[guid]) && !empty($input[guid])){
		$webinar = get_entity($input[guid], 'object');
		if (!$webinar || !$webinar->canEdit()) {
			register_error(elgg_echo('webinar:error:no_save'));
			forward(REFERER);
		}
		$new_webinar = false;
	}else{
		$webinar = new ElggWebinar();
		$new_webinar = true;
	}
	
	foreach ($input as $field => $value) {
		$webinar->$field = $value;
	}
	
	if ($guid = $webinar->save()) {
	
		elgg_clear_sticky_form('webinar');
	
		system_message(elgg_echo('webinar:saved'));
	
		if ($new_webinar) {
//			$webinar->logout_url = $webinar->getURL();
//			$webinar->save();
		
			add_to_river('river/object/webinar/create', 'create', elgg_get_logged_in_user_guid(), $guid);
		}
		
		if($webinar->enterprise == 'on'){
			//@todo notice when a user has already paid for enterprise and dont ask again
			$seller = get_user_by_username('mark');
			$pay_url = elgg_add_action_tokens_to_url('action/pay/basket/add?type_guid=' . $webinar->getGUID() .'&seller_guid='.$seller->guid.'&title=' . $webinar->title . '&description=' . $webinar->description  . '&price=1&quantity=1');
			forward($pay_url, 301);
		}
		
		forward($webinar->getURL());
	}
}

register_error(elgg_echo('pages:error:no_save'));
forward(REFERER);

	
/*
		if (is_plugin_enabled('event_calendar')){
			if ($isDated && is_array($slots)){
				$slot = $slots[$index];
				$event = $webinar->getEvent();
				if ($event){
					$event->start_date = $slot->start_date;
					$event->end_date = $slot->end_date;
					$event->start_time = $slot->start_time;
					$event->end_time = $slot->end_time;
				}else{
					$event = new ElggObject();
					$event->subtype = 'event_calendar';
					$event->owner_guid = get_loggedin_userid();
					$event->container_guid = $webinar->container_guid;
					$event->access_id = $webinar->access_id;
					$event->title = $webinar->title;
					$event->description = $webinar->description;
					$event->venue = $webinar->getURL();
					$event->start_date = $slot->start_date;
					$event->end_date = $slot->end_date;
					$event->start_time = $slot->start_time;
					$event->end_time = $slot->end_time;
					$event->region = '';
					$event->event_type = 'webinar';
					$event->fees = '';
					$event->contact = '';
					$event->organiser = '';
					$event->event_tags = $webinar->tags;
					$event->long_description = '';
				}
				if (!$event->save() || !add_entity_relationship($event->guid, 'webinar', $webinar->guid)){
					system_message(elgg_echo("webinar:event:create:failed"));
					register_error(elgg_echo("webinar:event:create:failed"));
				}
			}
		}
*/
