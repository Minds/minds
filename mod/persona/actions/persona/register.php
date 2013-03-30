<?php

    if (elgg_is_logged_in()) {
	$user = elgg_get_logged_in_user_entity();
	if ($user->persona_status == 'prereg') {
	    
	    $username = get_input('username');
	    $name = get_input('name');
	    
	    if (!validate_username($username) || get_user_by_username($username)) {
		register_error(elgg_echo('username:invalid'));
		forward(REFERER);
	    }
	    
	    if (empty($name)) {
		register_error(elgg_echo('name:invalid'));
		forward(REFERER);
	    }
	    
	    $user->username = $username;
	    $user->name = $name;
	    $user->persona_status = 'yes';
	    if ($user->save()) {
		
		system_message(elgg_echo('persona:registered'));
		forward($user->getURL());
		
	    }
	    
	}
    }