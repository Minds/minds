<?php
	global $CONFIG;

	gatekeeper();

	$user_guid = get_input('user_guid', get_loggedin_userid());
	$webinar_guid = get_input('webinar_guid');

	$user = get_entity($user_guid);
	$webinar = get_entity($webinar_guid);

	if (($user instanceof ElggUser) && ($webinar instanceof Elggwebinar))
	{
		elgg_load_library('elgg:webinar');
		
		if ($webinar->isRegistered($user)) {
			if ($webinar->unsubscribe($user)){
				system_message(elgg_echo("webinar:unsubscribe:success"));
				forward($webinar->getURL());
				exit;
			}else{
				system_message(elgg_echo("webinar:unsubscribe:failed"));
				register_error(elgg_echo("webinar:unsubscribe:failed"));
			}
		}else{
			system_message(elgg_echo("webinar:unsubscribe:impossible"));
			register_error(elgg_echo("webinar:unsubscribe:impossible"));
		}
	}
	else
		register_error(elgg_echo("webinar:unsubscribe:crash"));

	forward($_SERVER['HTTP_REFERER']);
	exit;
?>
