<?php
/**
 * Functions for Elgg's access system for entities, metadata, and annotations.
 *
 * Access is generally saved in the database as access_id.  This corresponds to
 * one of the ACCESS_* constants defined in {@link elgglib.php} or the ID of an
 * access collection.
 *
 * @package Elgg.Core
 * @subpackage Access
 * @link http://docs.elgg.org/Access
 */

/**
 * Return an ElggCache static variable cache for the access caches
 *
 * @staticvar ElggStaticVariableCache $access_cache
 * @return \ElggStaticVariableCache
 * @access private
 */
function _elgg_get_access_cache() {
	/**
	 * A default filestore cache using the dataroot.
	 */
	static $access_cache;

	if (!$access_cache) {
		$access_cache = new ElggStaticVariableCache('access');
	}

	return $access_cache;
}

/**
 * Gets the default access permission.
 *
 * This returns the default access level for the site or optionally for the user.
 *
 * @param ElggUser $user Get the user's default access. Defaults to logged in user.
 *
 * @return int default access id (see ACCESS defines in elgglib.php)
 * @link http://docs.elgg.org/Access
 */
function get_default_access(ElggUser $user = null) {
	global $CONFIG;

	if (!isset($CONFIG->allow_user_default_access) || !$CONFIG->allow_user_default_access) {
		return (int) $CONFIG->default_access;
	}

	if (!($user) && (!$user = elgg_get_logged_in_user_entity())) {
		return (int) $CONFIG->default_access;
	}

	if (false !== ($default_access = $user->getPrivateSetting('elgg_default_access'))) {
		return $default_access;
	} else {
		return (int) $CONFIG->default_access;
	}
}

/**
 * Allow disabled entities and metadata to be returned by getter functions
 *
 * @todo Replace this with query object!
 * @global bool $ENTITY_SHOW_HIDDEN_OVERRIDE
 * @access private
 */
$ENTITY_SHOW_HIDDEN_OVERRIDE = false;

/**
 * Show or hide disabled entities.
 *
 * @param bool $show_hidden Show disabled entities.
 * @return void
 * @access private
 */
function access_show_hidden_entities($show_hidden) {
	global $ENTITY_SHOW_HIDDEN_OVERRIDE;
	$ENTITY_SHOW_HIDDEN_OVERRIDE = $show_hidden;
}

/**
 * Return current status of showing disabled entities.
 *
 * @return bool
 * @access private
 */
function access_get_show_hidden_status() {
	global $ENTITY_SHOW_HIDDEN_OVERRIDE;
	return $ENTITY_SHOW_HIDDEN_OVERRIDE;
}

/**
 * Set if entity access system should be ignored.
 *
 * The access system will not return entities in any getter
 * functions if the user doesn't have access.
 *
 * @internal For performance reasons this is done at the database access clause level.
 *
 * @tip Use this to access entities in automated scripts
 * when no user is logged in.
 *
 * @note This clears the access cache.
 *
 * @warning This will not show disabled entities.
 * Use {@link access_show_hidden_entities()} to access disabled entities.
 *
 * @param bool $ignore If true, disables all access checks.
 *
 * @return bool Previous ignore_access setting.
 * @since 1.7.0
 * @see http://docs.elgg.org/Access/IgnoreAccess
 * @see elgg_get_ignore_access()
 */
function elgg_set_ignore_access($ignore = true) {
	$cache = _elgg_get_access_cache();
	$cache->clear();
	$elgg_access = elgg_get_access_object();
	return $elgg_access->setIgnoreAccess($ignore);
}

/**
 * Get current ignore access setting.
 *
 * @return bool
 * @since 1.7.0
 * @see http://docs.elgg.org/Access/IgnoreAccess
 * @see elgg_set_ignore_access()
 */
function elgg_get_ignore_access() {
	return elgg_get_access_object()->getIgnoreAccess();
}

/**
 * Decides if the access system should be ignored for a user.
 *
 * Returns true (meaning ignore access) if either of these 2 conditions are true:
 *   1) an admin user guid is passed to this function.
 *   2) {@link elgg_get_ignore_access()} returns true.
 *
 * @see elgg_set_ignore_access()
 *
 * @param int $user_guid The user to check against.
 *
 * @return bool
 * @since 1.7.0
 */
function elgg_check_access_overrides($user_guid = 0) {
	if (!$user_guid || $user_guid <= 0) {
		$is_admin = false;
	} else {
		$is_admin = elgg_is_admin_user($user_guid);
	}

	return ($is_admin || elgg_get_ignore_access());
}

/**
 * Returns the ElggAccess object.
 *
 * // @todo comment is incomplete
 * This is used to
 *
 * @return ElggAccess
 * @since 1.7.0
 * @access private
 */
function elgg_get_access_object() {
	static $elgg_access;

	if (!$elgg_access) {
		$elgg_access = new ElggAccess();
	}

	return $elgg_access;
}

/**
 * A flag to set if Elgg's access initialization is finished.
 *
 * @global bool $init_finished
 * @access private
 * @todo This is required to tell the access system to start caching because
 * calls are made while in ignore access mode and before the user is logged in.
 */
$init_finished = false;

/**
 * A quick and dirty way to make sure the access permissions have been correctly set up
 *
 * @elgg_event_handler init system
 * @todo Invesigate
 *
 * @return void
 */
function access_init() {
	global $init_finished;
	$init_finished = true;
}

/**
 * Overrides the access system if appropriate.
 *
 * Allows admin users and calls after {@link elgg_set_ignore_access} to
 * bypass the access system.
 *
 * Registered for the 'permissions_check', 'all' and the
 * 'container_permissions_check', 'all' plugin hooks.
 *
 * Returns true to override the access system or null if no change is needed.
 *
 * @param string $hook
 * @param string $type
 * @param bool $value
 * @param array $params
 * @return true|null
 * @access private
 */
function elgg_override_permissions($hook, $type, $value, $params) {
	$user = elgg_extract('user', $params);
	if ($user) {
		$user_guid = $user->getGUID();
	} else {
		$user_guid = elgg_get_logged_in_user_guid();
	}

	// don't do this so ignore access still works with no one logged in
	//if (!$user instanceof ElggUser) {
	//	return false;
	//}

	// check for admin
	if ($user_guid && elgg_is_admin_user($user_guid)) {
		return true;
	}

	// check access overrides
	if ((elgg_check_access_overrides($user_guid))) {
		return true;
	}

	// consult other hooks
	return NULL;
}

// Tell the access functions the system has booted, plugins are loaded,
// and the user is logged in so it can start caching
elgg_register_event_handler('ready', 'system', 'access_init');

// For overrided permissions
elgg_register_plugin_hook_handler('permissions_check', 'all', 'elgg_override_permissions');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'elgg_override_permissions');
