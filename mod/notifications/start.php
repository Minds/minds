<?php
/**
 * Elgg notifications plugin
 *
 * @package ElggNotifications
 */

elgg_register_event_handler('init', 'system', 'notifications_plugin_init');

function notifications_plugin_init() {
	
	/*$mail = new ElggNotificationEmail();
	$mail->subject = 'Your ' . $subscription . ' digest';
	$mail->subscription = $subscription;
	$mail->save();*/
	//$mail->send();
	
	elgg_register_plugin_hook_handler('cron', 'minute', 'notifications_cron_handler');
	elgg_register_plugin_hook_handler('cron', 'daily', 'notifications_cron_handler');
	elgg_register_plugin_hook_handler('cron', 'weekly', 'notifications_cron_handler');
	add_subtype('notificaiton', 'email', 'ElggNotificationEmail');

	elgg_extend_view('css/elgg','notifications/css');

	elgg_register_page_handler('notifications', 'notifications_page_handler');

	elgg_register_event_handler('pagesetup', 'system', 'notifications_plugin_pagesetup');
	
	elgg_register_event_handler('pagesetup', 'system', 'notification_notifier');

	// Unset the default notification settings
	elgg_unregister_plugin_hook_handler('usersettings:save', 'user', 'notification_user_settings_save');
	//elgg_unextend_view('forms/account/settings', 'core/settings/account/notifications');

	// update notifications based on relationships changing
	elgg_register_event_handler('delete', 'member', 'notifications_relationship_remove');
	elgg_register_event_handler('delete', 'friend', 'notifications_relationship_remove');

	// update notifications when new friend or access collection membership
	elgg_register_event_handler('create', 'friend', 'notifications_update_friend_notify');
	elgg_register_plugin_hook_handler('access:collections:add_user', 'collection', 'notifications_update_collection_notify');
	
	elgg_register_event_handler('create', 'object', 'notifications_notify');
	elgg_register_event_handler('create', 'metadata', 'notifications_notify');
	elgg_register_event_handler('create', 'annotation', 'notifications_notify');
	
	$notification_js = elgg_get_simplecache_url('js', 'notifications/notify');
	elgg_register_js('elgg.notifications', $notification_js, 'footer');
	elgg_load_js('elgg.notifications');
	

	$actions_base = elgg_get_plugins_path() . 'notifications/actions';
	elgg_register_action("notificationsettings/save", "$actions_base/save.php");
	elgg_register_action("notificationsettings/groupsave", "$actions_base/groupsave.php");
}

/**
 * Route page requests
 *
 * @param array $page Array of url parameters
 * @return bool
 */
function notifications_page_handler($page) {

	gatekeeper();
	$current_user = elgg_get_logged_in_user_entity();

	// default to personal notifications
	if (!isset($page[0])) {
		$page[0] = 'personal';
	}
	if (!isset($page[1])) {
		forward("notifications/{$page[0]}/{$current_user->username}");
	}

	$user = get_user_by_username($page[1]);
	if (($user->guid != $current_user->guid) && !$current_user->isAdmin()) {
		forward();
	}

	$base = elgg_get_plugins_path() . 'notifications';

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
		
		if (elgg_is_active_plugin('groups')) {
			$params = array(
				'name' => '2_group_notify',
				'text' => elgg_echo('notifications:subscriptions:changesettings:groups'),
				'href' => "notifications/group/{$user->username}",
			);
			elgg_register_menu_item('page', $params);
		}
	}

}

/**
 * Update notifications when a relationship is deleted
 *
 * @param string $event
 * @param string $object_type
 * @param object $relationship
 */
function notifications_relationship_remove($event, $object_type, $relationship) {
	global $NOTIFICATION_HANDLERS;

	$user_guid = $relationship->guid_one;
	$object_guid = $relationship->guid_two;

	// loop through all notification types
	foreach($NOTIFICATION_HANDLERS as $method => $foo) {
		remove_entity_relationship($user_guid, "notify{$method}", $object_guid);
	}
}

/**
 * Turn on notifications for new friends if all friend notifications is on
 *
 * @param string $event
 * @param string $object_type
 * @param object $relationship
 */
function notifications_update_friend_notify($event, $object_type, $relationship) {
	global $NOTIFICATION_HANDLERS;

	$user_guid = $relationship->guid_one;
	$friend_guid = $relationship->guid_two;

	$user = get_entity($user_guid);

	// loop through all notification types
	foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
		$metaname = 'collections_notifications_preferences_' . $method;
		$collections_preferences = $user->$metaname;
		if ($collections_preferences) {
			if (!empty($collections_preferences) && !is_array($collections_preferences)) {
				$collections_preferences = array($collections_preferences);
			}
			if (is_array($collections_preferences)) {
				// -1 means all friends is on - should be a define
				if (in_array(-1, $collections_preferences)) {
					add_entity_relationship($user_guid, 'notify' . $method, $friend_guid);
				}
			}
		}
	}
}

/**
 * Update notifications for changes in access collection membership.
 *
 * This function assumes that only friends can belong to access collections.
 *
 * @param string $event
 * @param string $object_type
 * @param bool $returnvalue
 * @param array $params
 */
function notifications_update_collection_notify($event, $object_type, $returnvalue, $params) {
	global $NOTIFICATION_HANDLERS;

	// only update notifications for user owned collections
	$collection_id = $params['collection_id'];
	$collection = get_access_collection($collection_id);
	$user = get_entity($collection->owner_guid);
	if (!($user instanceof ElggUser)) {
		return $returnvalue;
	}

	$member_guid = $params['user_guid'];

	// loop through all notification types
	foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
		$metaname = 'collections_notifications_preferences_' . $method;
		$collections_preferences = $user->$metaname;
		if (!$collections_preferences) {
			continue;
		}
		if (!is_array($collections_preferences)) {
			$collections_preferences = array($collections_preferences);
		}
		if (in_array(-1, $collections_preferences)) {
			// if "all friends" notify is on, we don't change any notifications
			// since must be a friend to be in an access collection
			continue;
		}
		if (in_array($collection_id, $collections_preferences)) {
			// notifications are on for this collection so we add/remove
			if ($event == 'access:collections:add_user') {
				add_entity_relationship($user->guid, "notify$method", $member_guid);
			} elseif ($event == 'access:collections:remove_user') {
				// removing someone from an access collection is not a guarantee
				// that they should be removed from notifications
				//remove_entity_relationship($user->guid, "notify$method", $member_guid);
			}
		}
	}
}

/**
 * Display notification of new messages in topbar
 */
function notification_notifier() {
	if (elgg_is_logged_in()) {
		
		elgg_extend_view('page/elements/topbar', 'notifications/popup');
		
		$class = "notification notifier entypo";
		$text = "<span class='$class'>&#59141;</span>";
		$tooltip = elgg_echo("notification");
		
		// get unread messages
		$num_notifications = (int)notifications_count_unread();
		if ($num_notifications > 0) {
			$class = "notification notifier entypo new";
			$text = "<span class='$class'>&#59141;" .
						"<span class=\"notification-new\">$num_notifications</span>" .
					  "</span>";
			$tooltip .= " (" . elgg_echo("notifications:unread", array($num_notifications)) . ")";
		}

		elgg_register_menu_item('notifications', array(
			'name' => 'notification',
			'href' => '#notification',
			'rel' => 'popup',
			'text' => $text,
			'priority' => 600,
			'class' => 'entypo',
			'title' => $tooltip,
			'id'=>'notify_button',
			'section' => 'alt',//this is custom to the minds theme. 
		));
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
//get a list of notifications
function get_notifications($user_guid, $limit = 12, $offset = 0){
	global $DB;

	$notifications = elgg_list_entities(array('attrs'=>array('namespace'=>'notifications:'.$to_guid), 'limit'=>$limit,'offset'=>$offset ));

	return $notifications;
}
/**
 * Mark notifications as read
 *
 */
function notifications_count_unread(){
	$user = elgg_get_logged_in_user_entity();
	return $user->notifications_count;	
}

/** 
 * Increase a users notifications counter
 */
function notifications_increase_counter($user_guid){
	elgg_set_ignore_access(true);
	$user = get_entity($user_guid, 'user');
	$user->notifications_count++;
	$user->save();
	elgg_set_ignore_access(false);
}
/** 
 * Reset a user notifications counter
 */
function notifications_reset_counter($user_guid = NULL){
	if(!$user_guid){
		$user_guid = elgg_get_logged_in_user_guid();
	}
	elgg_set_ignore_access(true);
	$user = get_entity($user_guid, 'user');
	$user->notifications_count = 0;
	$user->save();
	elgg_set_ignore_access(false);
}


function notifications_cron_handler($hook, $type, $params, $return){
	
	$queue = elgg_get_entities(array('type'=>'notification', 'subtype'=>'email', 'limit'=>0));
	
	foreach($queue as $q){
		if($q->send()){
			echo 'sent';
		}
	}

	$mail = new ElggNotificationEmail();
	switch($type){
		case 'daily':
			$mail = new ElggNotificationEmail();
			$mail->subject = 'Your ' . $subscription . ' digest';
			$mail->subscription = $type;
			$mail->send();
			break;
		case 'weekly':
			$mail = new ElggNotificationEmail();
			$mail->subject = 'Your ' . $subscription . ' digest';
			$mail->subscription = $type;
			$mail->send();
			break;
	}
	
}

