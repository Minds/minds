<?php
/**
 * Logout as the current user, back to the original user.
 */

$user_guid = isset($_SESSION['login_as_original_user_guid']) ? $_SESSION['login_as_original_user_guid'] : NULL;
$user = get_entity($user_guid);

$persistent = isset($_SESSION['login_as_original_persistent']) ? $_SESSION['login_as_original_persistent'] : FALSE;

if (!$user instanceof ElggUser || !$user->isadmin()) {
	register_error(elgg_echo('login_as:unknown_user'));
} else {
	if (login($user, $persistent)) {
		unset($_SESSION['login_as_original_user_guid']);
		unset($_SESSION['login_as_original_persistent']);
		system_message(elgg_echo('login_as:logged_in_as_user', array($user->username)));
	} else {
		register_error(elgg_echo('login_as:could_not_login_as_user', array($user->username)));
	}
}

forward(REFERER);
