<?php
/**
 * Elgg notifications plugin
 *
 * @package ElggNotifications
 */

elgg_register_event_handler('init', 'system', function(){
	new minds\plugin\notifications\notifications();
	
	//uncomment below to test
	//elgg_trigger_plugin_hook('notification', 'all', array('to'=>array(elgg_get_logged_in_user_guid()), 'object_guid'=>elgg_get_logged_in_user_guid()));
});


/**
 * Notification settings sidebar menu
 *
 */
function notifications_plugin_pagesetup() {
	
if (elgg_get_context() == "settings" && elgg_get_logged_in_user_guid()) {

		$user = elgg_get_page_owner_entity();
		if (!$user) {
			$user = elgg_get_logged_in_user_entity();
		}

		$params = array(
			'name' => '2_a_user_notify',
			'text' => elgg_echo('notifications:subscriptions:changesettings'),
			'href' => "notifications/personal/{$user->username}",
		);
		elgg_register_menu_item('page', $params);
		
		/*if (elgg_is_active_plugin('groups')) {
			$params = array(
				'name' => '2_group_notify',
				'text' => elgg_echo('notifications:subscriptions:changesettings:groups'),
				'href' => "notifications/group/{$user->username}",
			);
			elgg_register_menu_item('page', $params);
		}*/
	}

}

/**
 * Create a notification
 *
 * @param $to int/arr - guid(s) of the user to recieve the notification
 * @param $from int - guid of the user making the notification
 * @param $object object - the entity or object in question
 * @param $params array - any further information, such as a comment
 *
 * @return bool - success or failed
 */
function notification_create($to, $from, $object, $params){
	return elgg_trigger_plugin_hook('notification', 'all', array('to'=>$to, 'from'=>$from, 'object_guid'=>$object, 'params'=>$params));

	//if the user and from are not the same then send!
	//if($to != $from){
	foreach($to as $t){
		if($t != $from){
			$notification = new ElggNotification();
			$notification->to_guid = $t;
			$notification->object_guid = $object;
			$notification->from_guid = $from;
			$notification->notification_view = $params['notification_view'];
			$notification->description = $params['description'];
			$notification->read = 0;
			$notification->access_id = 2;
			$notification->params = serialize($params);
			$notification->time_created = time();
		
			$notification->save();
		}
	}
	//}
	
	return true;
	
}
