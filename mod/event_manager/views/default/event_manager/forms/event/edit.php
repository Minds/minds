<?php 

	// defaults
	$fields = array(
			"guid" 					=> ELGG_ENTITIES_ANY_VALUE,
			"title" 				=> ELGG_ENTITIES_ANY_VALUE,
			"shortdescription" 		=> ELGG_ENTITIES_ANY_VALUE,
			"tags" 					=> ELGG_ENTITIES_ANY_VALUE,
			"description" 			=> ELGG_ENTITIES_ANY_VALUE,
			"comments_on"			=> 1,
			"venue"					=> ELGG_ENTITIES_ANY_VALUE,
			"location"				=> ELGG_ENTITIES_ANY_VALUE,
			"latitude"				=> ELGG_ENTITIES_ANY_VALUE,
			"longitude"				=> ELGG_ENTITIES_ANY_VALUE,
			"region"				=> ELGG_ENTITIES_ANY_VALUE,
			"event_type"			=> ELGG_ENTITIES_ANY_VALUE,
			"organizer"				=> ELGG_ENTITIES_ANY_VALUE,
			"start_day" 			=> time(),
			"start_time"			=> time(),
			"registration_ended" 	=> ELGG_ENTITIES_ANY_VALUE,
			"endregistration_day" 	=> ELGG_ENTITIES_ANY_VALUE,
			"with_program" 			=> ELGG_ENTITIES_ANY_VALUE,
			"registration_needed" 	=> ELGG_ENTITIES_ANY_VALUE,
			"register_nologin" 		=> ELGG_ENTITIES_ANY_VALUE,
			"show_attendees"		=> 1,
			"notify_onsignup"		=> ELGG_ENTITIES_ANY_VALUE,
			"max_attendees"		 	=> ELGG_ENTITIES_ANY_VALUE,
			"waiting_list_enabled"	=> ELGG_ENTITIES_ANY_VALUE,
			"access_id"				=> get_default_access(),
			"container_guid"		=> elgg_get_page_owner_entity()->getGUID(),
			"event_interested"		=> 1,
			"event_presenting"		=> 1,
			"event_exhibiting"		=> 1,
			"event_organizing"		=> 1,
		);
		
	$region_options = event_manager_event_region_options();
	$type_options = event_manager_event_type_options();
	
	if($event = $vars['entity']) {
		// edit mode
		$fields["guid"]			= $event->getGUID();
		$fields["location"]		= $event->getLocation();
		$fields["latitude"]		= $event->getLatitude();
		$fields["longitude"]	= $event->getLongitude();
		$fields["tags"]			= array_reverse(string_to_tag_array($event->tags));
		
		$start_time_hours = date('H', $event->start_time);
		$start_time_minutes = date('i', $event->start_time);	
		
		if($event->icontime) {
			$currentIcon = '<img src="'.$event->getIcon().'" />';
		}
		
		foreach($fields as $field => $value){
			if(!in_array($field, array("guid", "location", "latitude", "longitude"))){
				$fields[$field] = $event->$field;
			}
		}
	} else {
		// new mode
		if(!empty($_SESSION['createevent_values'])) {
			// check for empty fields that should revert to defaults
			foreach(array("start_day", "access_id") as $data){
				if($_SESSION['createevent_values'][$data] == ''){
					unset($_SESSION['createevent_values'][$data]);
				}
			}
			
			// merge defaults with session data
			$fields = array_merge($fields, $_SESSION['createevent_values']);
		}
	}
	
	$form_body .= '<a style="display: none;" href="'.EVENT_MANAGER_BASEURL.'/event/googlemaps/'.$fields["guid"].'" id="openGoogleMaps">google maps</a>';
	$form_body .= elgg_view('input/hidden', array('name' => 'latitude', 'id' => 'event_latitude', 'value' => $fields["latitude"]));
	$form_body .= elgg_view('input/hidden', array('name' => 'longitude', 'id' => 'event_longitude', 'value' => $fields["longitude"]));
	$form_body .= elgg_view('input/hidden', array('name' => 'guid', 'value' => $fields["guid"]));
	$form_body .= elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $fields["container_guid"]));
	
	$form_body .= "<table>";
	
	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:title') . " *</td><td>" . elgg_view('input/text', array('name' => 'title', 'value' => $fields["title"])) . "</td></tr>";

	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:shortdescription') . "</td><td>" . elgg_view('input/text', array('name' => 'shortdescription', 'value' => $fields["shortdescription"])) . "</td></tr>";
	
	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('tags') . " *</td><td>" . elgg_view('input/tags', array('name' => 'tags', 'value' => $fields["tags"])) . "</td></tr>";

	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:description') . "</td><td>" . elgg_view('input/longtext', array('name' => 'description', 'value' => $fields["description"])) . "</td></tr>";

	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:icon') . "</td><td>" . elgg_view('input/file', array('name' => 'icon')) . "</td></tr>";
	
	if($currentIcon) {
		$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:currenticon') . "</td><td>".$currentIcon."<br />".
		elgg_view('input/checkboxes', array('name' => 'delete_current_icon', 'id' => 'delete_current_icon', 'value' => 0, 'options' => 
		array(elgg_echo('event_manager:edit:form:delete_current_icon')=>'1')))."</td></tr>";
	}
	
	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:start_day') . " *</td>
	<td>" . elgg_view('input/date', array('name' => 'start_day', 'id' => 'start_day', 'value' => date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $fields["start_day"]))) . "</td></tr>";
	
	if($fields['with_program'])	{
		$start_time_hidden = ' style="display: none; "';
	}

	$form_body .= "<tr id='event_manager_start_time_pulldown' ".$start_time_hidden." ><td class='event_manager_event_edit_label'>" . elgg_echo("event_manager:edit:form:start_time") . "</td><td>". 
		event_manager_get_form_pulldown_hours('start_time_hours', $start_time_hours).
		event_manager_get_form_pulldown_minutes('start_time_minutes', $start_time_minutes)."</td>";

	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:organizer') . "</td><td>" . elgg_view('input/text', array('name' => 'organizer', 'value' => $fields["organizer"])) . "</td></tr>";

	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:venue') . "</td><td>" . elgg_view('input/text', array('name' => 'venue', 'value' => $fields["venue"])) . "</td></tr>";
	
	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:location') . "</td><td>" . elgg_view('input/text', array('name' => 'location', 'id' => 'openmaps', 'value' => $fields["location"], 'readonly' => true)) . "</td></tr>";
	
	if($region_options)	{
		$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:region') . "</td><td>" . elgg_view('input/dropdown', array('name' => 'region', 'value' => $fields["region"], 'options' => $region_options)) . "</td></tr>";
	}
	
	if($type_options) { 
		$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:type') . "</td><td>" . elgg_view('input/dropdown', array('name' => 'event_type', 'value' => $fields["event_type"], 'options' => $type_options)) . "</td></tr>";
	} 

	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:max_attendees') . "</td><td>" . elgg_view('input/text', array('name' => 'max_attendees', 'value' => $fields["max_attendees"])) . "</td></tr>";

	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:options') . "</td><td>";
	
	$form_body .=	elgg_view('input/checkboxes', array('name' => 'with_program', 'id' => 'with_program', 'value' => $fields["with_program"], 'options' => array(elgg_echo('event_manager:edit:form:with_program')=>'1')));
	$form_body .= 	elgg_view('input/checkboxes', array('name' => 'comments_on', 'value' => $fields["comments_on"], 'options' => array(elgg_echo('event_manager:edit:form:comments_on')=>'1')));
	$form_body .= 	elgg_view('input/checkboxes', array('name' => 'notify_onsignup', 'value' => $fields["notify_onsignup"], 'options' => array(elgg_echo('event_manager:edit:form:notify_onsignup')=>'1')));
	$form_body .= 	elgg_view('input/checkboxes', array('name' => 'registration_needed', 'value' => $fields["registration_needed"], 'options' => array(elgg_echo('event_manager:edit:form:registration_needed')=>'1')));
	$form_body .= 	elgg_view('input/checkboxes', array('name' => 'show_attendees', 'value' => $fields["show_attendees"], 'options' => array(elgg_echo('event_manager:edit:form:show_attendees')=>'1')));
	$form_body .= 	elgg_view('input/checkboxes', array('name' => 'waiting_list_enabled', 'value' => $fields["waiting_list_enabled"], 'options' => array(elgg_echo('event_manager:edit:form:waiting_list')=>'1')));
	$form_body .= 	elgg_view('input/checkboxes', array('name' => 'register_nologin', 'value' => $fields["register_nologin"], 'options' => array(elgg_echo('event_manager:edit:form:register_nologin')=>'1')));
	
	$form_body .= "</td></tr>";
	
	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:endregistration_day') . "</td><td>";
	
	$form_body .= elgg_view('input/date', array('name' => 'endregistration_day', 'id' => 'endregistration_day', 'value' => (($fields["endregistration_day"]!=0)?date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY,$fields["endregistration_day"]):''))) . "<br />";
	$form_body .= elgg_view('input/checkboxes', array('name' => 'registration_ended', 'value' => $fields["registration_ended"], 'options' => array(elgg_echo('event_manager:edit:form:registration_ended')=>'1')));
	
	$form_body .= "</td></tr><tr><td>&nbsp</td></tr>";
	
	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('event_manager:edit:form:rsvp_options') . "</td><td>";
	
	$form_body .= elgg_view('input/checkboxes', array('name' => 'event_interested', 'id' => 'event_interested', 'value' => $fields["event_interested"], 'options' => array(elgg_echo('event_manager:event:relationship:event_interested')=>'1')));
	$form_body .= elgg_view('input/checkboxes', array('name' => 'event_presenting', 'id' => 'event_presenting', 'value' => $fields["event_presenting"], 'options' => array(elgg_echo('event_manager:event:relationship:event_presenting')=>'1')));
	$form_body .= elgg_view('input/checkboxes', array('name' => 'event_exhibiting', 'id' => 'event_exhibiting', 'value' => $fields["event_exhibiting"], 'options' => array(elgg_echo('event_manager:event:relationship:event_exhibiting')=>'1')));
	$form_body .= elgg_view('input/checkboxes', array('name' => 'event_organizing', 'id' => 'event_organizing', 'value' => $fields["event_organizing"], 'options' => array(elgg_echo('event_manager:event:relationship:event_organizing')=>'1')));
	
	$form_body .= "</td></tr>";
	
	$form_body .= "<tr><td class='event_manager_event_edit_label'>" . elgg_echo('access') . "</td><td>" . elgg_view('input/access', array('name' => 'access_id', 'value' => $fields["access_id"])) . "</td></tr>";
	
	$form_body .= "</table>";
					
	$form_body .= elgg_view('input/submit', array('value' => elgg_echo('save')));
	$form_body .= '<div class="event_manager_required">(* = '.elgg_echo('requiredfields').')</div>';
	
	$form = elgg_view('input/form', array(
									'id' => 'event_manager_event_edit', 
									'name' 	=> 'event_manager_event_edit', 
									'action' => '/action/event_manager/event/edit', 
									'enctype' => 'multipart/form-data', 
									'body' => $form_body
									));
	
	echo elgg_view_module("main", "", $form);
	
	// unset sticky data TODO: replace with sticky forms functionality
	$_SESSION['createevent_values'] = null;