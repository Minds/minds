<?php

require(dirname(dirname(__FILE__)).'/engine/start.php');

$db = new DatabaseCall('entities_by_time');
$group_guids = $db->getRow('group', array('limit'=>400));

foreach($group_guids as $guid=>$ts){
	$group = get_entity($guid); 
	if(!$group){
		$db->removeAttributes('group', array($guid));
	}
}
