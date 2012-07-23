<?php 

	$event_guid = get_input("event_guid");
	$question_guid = get_input("question_guid");
	
	echo elgg_view("event_manager/forms/registrationform/question", array('event_guid' => $event_guid, 'question_guid' => $question_guid));
	
	exit();