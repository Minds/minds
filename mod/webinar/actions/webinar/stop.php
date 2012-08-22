<?php
gatekeeper();

$webinar_guid = get_input('webinar_guid');

$webinar = get_entity($webinar_guid);

if ($webinar && $webinar instanceof ElggWebinar){
	
	if ($webinar->isRunning()){
		$webinar->status = 'done';
		$webinar->save();
	}else{
		system_message(elgg_echo("webinar:isNotRunning"));
	}
}else{
	register_error(elgg_echo("webinar:stop:failed"));
}
forward($_SERVER['HTTP_REFERER']);
exit;
?>
