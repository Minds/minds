<?php

/**
 * Elgg notifications
 *
 * @package ElggNotifications
 */

$current_user = elgg_get_logged_in_user_entity();
$db = new Minds\Core\Data\Call('entities_by_time');

$guid = (int) get_input('guid', 0);
if (!$guid || !($user = get_entity($guid,'user'))) {
	forward();
}
if (($user->guid != $current_user->guid) && !$current_user->isAdmin()) {
	forward();
}

$namespace = 'notification:subscriptions:';
$subscription = get_input('subscription', 'weekly');

if($current_subscription = $current_user->notification_subscription){
	$db->removeAttributes($namespace . $current_subscription, array($current_user->guid));
}
	
$current_user->notification_subscription = $subscription;
$current_user->save();	
$db->insert("$namespace$subscription", array($current_user->guid => $current_user->guid));

system_message(elgg_echo('notifications:subscriptions:success'));

forward(REFERER);
