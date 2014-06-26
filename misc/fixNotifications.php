<?php

require_once(dirname(dirname(__FILE__)) . '/engine/start.php');
elgg_set_ignore_access(true);


$db = new \minds\core\data\call('entities_by_time');
$notifications = $db->getRow('notifications:100000000000000134', array('limit'=>40));
$db->removeAttributes('notifications:100000000000000134', $notifications);
exit;

$user_guid =  $db->getRow('user', array('limit'=> 100, 'reversed'=>false));
foreach($user_guid as $guid){
	$guids = $db->getRow('notifications:'.$guid, array('limit'=> 40));
	if(!$guids)
		continue;	

	$notifications = elgg_get_entities(array('guids'=>$guids));
	foreach($notifications as $notification){
		if(!$notification->notification_view){
			$notification->delete();
			echo "Delete $notification->guid \n";
		}
	}
}
