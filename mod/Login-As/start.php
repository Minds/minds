<?php
/**
 * Provides an entry in the user hover menu for admins to login as the user.
 */

elgg_register_event_handler('init', 'system', 'login_as_init');

/**
 * Init
 */
function login_as_init() {

	// user hover menu and topbar links.
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'login_as_user_hover_menu');
	elgg_register_event_handler('pagesetup', 'system', 'login_as_add_topbar_link');
	elgg_extend_view('css/elgg', 'login_as/css');

	$action_path = dirname(__FILE__) . '/actions/';
	elgg_register_action('login_as', $action_path . 'login_as.php', 'admin');
	elgg_register_action('logout_as', $action_path . 'logout_as.php');
}

/**
 * Add Login As to user hover menu for admins
 *
 * @param string $hook
 * @param string $type
 * @param array  $menu
 * @param array  $params
 */
function login_as_user_hover_menu($hook, $type, $menu, $params) {
	$user = $params['entity'];
	$logged_in_user = elgg_get_logged_in_user_entity();

	// Don't show menu on self.
	if ($logged_in_user == $user) {
		return $menu;
	}

	$url = "action/login_as?user_guid=$user->guid";
	$menu[] = ElggMenuItem::factory(array(
		'name' => 'login_as',
		'text' => elgg_echo('login_as:login_as'),
		'href' => $url,
		'is_action' => true,
		'section' => 'admin'
	));

	return $menu;
}

/**
 * Add a menu item to the topbar menu for logging out of an account
 */
function login_as_add_topbar_link() {
	$original_user_guid = isset($_SESSION['login_as_original_user_guid']) ? $_SESSION['login_as_original_user_guid'] : NULL;

	// short circuit view if not logged in as someone else.
	if (!$original_user_guid) {
		return;
	}

	$title = elgg_echo('login_as:return_to_user', array(
		elgg_get_logged_in_user_entity()->username,
		get_entity($original_user_guid)->username
	));

	// hack to work around bug in Elgg 1.8.0 and fixed in Elgg 1.8.1
	global $CONFIG;
	$CONFIG->pagesetupdone = true;

	$html = elgg_view('login_as/topbar_return', array('user_guid' => $original_user_guid));
	elgg_register_menu_item('topbar', array(
		'name' => 'login_as_return',
		'text' => $html,
		'href' => 'action/logout_as',
		'is_action' => true,
		'title' => $title,
		'class' => 'login-as-topbar',
		'priority' => 700,
	));
}
