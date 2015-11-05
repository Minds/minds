<?php
/**
 * Ban users plugin
 *
 * Elgg core uses the metadata 'ban_reason' for storing the reason for the banning.
 * We store the release time as a 'ban_release' annotation on the user and we add
 * a 'banned' annotation to serve as a record that the user has been banned in the
 * past. This is what allows us to count the number of times the user has been banned.
 */

elgg_register_event_handler('init', 'system', 'ban_init');

function ban_init() {

	elgg_register_plugin_hook_handler('cron', 'hourly', 'ban_cron');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'ban_user_hover_menu');

	elgg_extend_view('css/admin', 'ban/css');

	elgg_register_admin_menu_item('administer', 'ban_list', 'users');

	elgg_register_widget_type('banned_users', elgg_echo('ban:list:title'), elgg_echo('ban:list:title'), 'admin');

}

/**
 * Add Ban user hover menu for admins
 *
 * @param string $hook
 * @param string $type
 * @param array  $menu
 * @param array  $params
 */
function ban_user_hover_menu($hook, $type, $menu, $params) {
	$user = $params['entity'];

	if (elgg_get_logged_in_user_guid() == $user->getGUID) {
		return $menu;
	}

	if ($user->isBanned()) {
		$menu[] = ElggMenuItem::factory(array(
			'name' => 'unban',
			'text' => elgg_echo('unban'),
			'href' => "action/ban/unban?guid=$user->guid",
			'is_action' => true,
			'section' => 'admin',
		));
	} else {
		$menu[] = ElggMenuItem::factory(array(
			'name' => 'ban',
			'text' => elgg_echo('ban'),
			'href' => "admin/users/ban?guid=$user->guid",
			'section' => 'admin',
		));
	}

	return $menu;
}

/**
 * Unban users whose timeouts have expired
 *
 * @return void
 */
function ban_cron() {
	global $CONFIG;

	$previous = elgg_set_ignore_access();

	$params = array(
		'type'   => 'user',
		'annotation_names' => array('ban_release'),
		'joins'  => array("JOIN {$CONFIG->dbprefix}users_entity u on e.guid = u.guid"),
		'wheres' => array("u.banned='yes'"),
	);

	$now = time();
	$users = elgg_get_entities_from_annotations($params);

	foreach ($users as $user) {
		$releases = elgg_get_annotations(array(
			'guid' => $user->guid,
			'annotation_name' => 'ban_release',
			'limit' => 1,
			'order' => 'n_table.time_created desc',
		));

		foreach ($releases as $release) {
			if ($release->value < $now) {
				if ($user->unban()) {
					$release->delete();
				}
			}
		}
	}

	elgg_set_ignore_access($previous);
}

/**
 * Override the user/default view for banned users in banned list
 */
function banned_user_view($hook, $type, $return, $params) {
	return elgg_view('ban/banned_user', $params['vars']);
}
