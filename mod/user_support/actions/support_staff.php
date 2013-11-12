<?php

	$user_guid = (int) get_input("guid");
	
	if (!empty($user_guid)) {
		if ($user = get_user($user_guid)) {
			if ($user->support_staff) {
				unset($user->support_staff);
				system_message(elgg_echo("user_support:action:support_staff:removed"));
			} else {
				$user->support_staff = time();
				system_message(elgg_echo("user_support:action:support_staff:added"));
			}
		} else {
			register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}
	
	forward(REFERER);