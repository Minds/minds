<?php

require_once(dirname(dirname(__FILE__)) . '/engine/start.php');

$db = new DatabaseCall('entities_by_time');
$group_guids = $db->getRow('group');

foreach($group_guids as $guid => $ts){
	$group = get_entity($guid);
	if(!$group){
		echo "$guid is a dud \n";
		$db->removeAttributes('group', array($guid));
}
	
}
