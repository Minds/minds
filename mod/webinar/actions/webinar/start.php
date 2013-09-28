<?php
gatekeeper();

$webinar_guid = get_input('webinar_guid');

if ( ($webinar = get_entity($webinar_guid, 'object')) && $webinar instanceof ElggWebinar){
	
	if ($webinar->isUpcoming()){
		$webinar->status = 'running';
		$webinar->save();
		
		add_to_river('river/object/webinar/start','start',elgg_get_logged_in_user_guid(),$webinar->guid);
		
	}else{
		system_message(elgg_echo("webinar:isNotUpcomming"));
	}
	
}else{
	register_error(elgg_echo("webinar:start:failed"));
}
forward($_SERVER['HTTP_REFERER']);
exit;
