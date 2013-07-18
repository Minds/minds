<?php
/**
 * Login as the specified user
 *
 * Sets a flag in the session to let us know who the originally logged in user is.
 */

$user_guid = get_input('user_guid', 0);
$original_user = elgg_get_logged_in_user_entity();
$original_user_guid = $original_user->guid;

if (!$user = get_entity($user_guid)) {
	register_error(elgg_echo('login_as:unknown_user'));
	forward(REFERER);
}

// store the original persistent login state to restore on logout_as.
$persistent = FALSE;
if (isset($_COOKIE['elggperm'])) {
	$code = $_COOKIE['elggperm'];
	$code = md5($code);
	if (($original_perm_user = get_user_by_code($code)) && $original_user->guid == $original_perm_user->guid) {
		$persistent = TRUE;
	}
}

if (login($user)) {
	$_SESSION['login_as_original_user_guid'] = $original_user_guid;
	$_SESSION['login_as_original_persistent'] = $persistent;
	system_message(elgg_echo('login_as:logged_in_as_user', array($user->username)));
} else {
	register_error(elgg_echo('login_as:could_not_login_as_user', array($user->username)));
}

forward(REFERER);
