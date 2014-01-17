<?php

require_once('engine/start.php');

$users = elgg_get_entities(array('type'=>'user', 'limit'=>999999));

foreach($users as $user){
	$user->notification_subscription = 'daily';
	$guid = $user->save();
	$subscription = 'daily';
	$namespace = 'notification:subscriptions:';
	db_insert("$namespace$subscription", array('type'=>'entities_by_time', $user->guid => $user->guid));
	echo "$guid \n";
}
