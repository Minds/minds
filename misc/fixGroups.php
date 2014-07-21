<?php

require_once(dirname(dirname(__FILE__)) . '/engine/start.php');
elgg_set_ignore_access();
exit;
$group = new ElggGroup('302433321919451136');


$members = $group->getMembers(100);
foreach($members as $member){
	$group->join($member);
}
exit;

$db = new DatabaseCall('entities_by_time');
$group_guids = $db->getRow('group');

foreach($group_guids as $guid => $ts){
	$group = get_entity($guid);
	if(!$group){
		echo "$guid is a dud \n";
		$db->removeAttributes('group', array($guid));
}
	
}
