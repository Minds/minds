<?php
/**
 * Notifications page handler
 */
namespace minds\plugin\notifications\pages;

use minds\core;
use minds\interfaces;

class view extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){

		\gatekeeper();
		$current_user = \elgg_get_logged_in_user_entity();
	
		// default to personal notifications
		if (!isset($page[0])) {
			$page[0] = 'personal';
		}
		if (!isset($page[1])) {
			\forward("notifications/{$page[0]}/{$current_user->username}");
		}
	
		$user = \get_user_by_username($page[1]);
		if (($user->guid != $current_user->guid) && !$current_user->isAdmin()) {
			\forward();
		}
	
		$base = \elgg_get_plugins_path() . 'notifications';
	
		// note: $user passed in
		switch ($page[0]) {
			case 'view':
				set_input('full', true);
				set_input('user_guid', $user->guid);
				require "$base/pages/notifications.php";
				break;
			case 'group':
				require "$base/groups.php";
				break;
			case 'personal':
				require "$base/index.php";
				break;
			default:
				return false;
		}
		return true;
	}
	
	public function post($pages){}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
