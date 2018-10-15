<?php
/**
 * Return the current logged in user, or NULL if no user is logged in.
 *
 * If no user can be found in the current session, a plugin
 * hook - 'session:get' 'user' to give plugin authors another
 * way to provide user details to the ACL system without touching the session.
 *
 * @return ElggUser
 */
function elgg_get_logged_in_user_entity() {
	return Minds\Core\Session::getLoggedinUser();
}

/**
 * Return the current logged in user by id.
 *
 * @see elgg_get_logged_in_user_entity()
 * @return int
 */
function elgg_get_logged_in_user_guid() {
	$user = elgg_get_logged_in_user_entity();
	if ($user) {
		return $user->guid;
	}

	return 0;
}

/**
 * Returns whether or not the user is currently logged in
 *
 * @return bool
 */
function elgg_is_logged_in() {
	return Minds\Core\Session::isLoggedin();
}

/**
 * Returns whether or not the user is currently logged in and that they are an admin user.
 *
 * @return bool
 */
function elgg_is_admin_logged_in() {
	$user = elgg_get_logged_in_user_entity();

	if ((elgg_is_logged_in()) && $user->isAdmin()) {
		return TRUE;
	}

	return FALSE;
}

