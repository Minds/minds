<?php
	global $CONFIG;

	gatekeeper();

	$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
	$webinar_guid = get_input('webinar_guid');

	$user = get_entity($user_guid);
	$webinar = get_entity($webinar_guid);

	if (($user instanceof ElggUser) && ($webinar instanceof ElggWebinar))
	{
		elgg_load_library('elgg:webinar');
		
		if (!$webinar->isAttendee($user)) {
			if (!$webinar->isRegistered($user)) {
				if ($webinar->subscribe($user))
				{
					add_to_river('river/relationship/registered/create','registered', $user->guid,$webinar->guid);
					
					system_message(elgg_echo("webinar:subscribe:success"));
		
					forward($webinar->getURL());
					exit;
				}else{
					system_message(elgg_echo("webinar:subscribe:failed"));
					register_error(elgg_echo("webinar:subscribe:failed"));
				}
			}else{
				system_message(elgg_echo("webinar:subscribe:duplicate"));
				register_error(elgg_echo("webinar:subscribe:duplicate"));
			}
		}
	}
	else
		register_error(elgg_echo("webinar:subscribe:crash"));

	forward($_SERVER['HTTP_REFERER']);
	exit;
?>
