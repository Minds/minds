<?php
/** 
 * This is an update to the schema to support relationships
 * 
 */
require(dirname(dirname(__FILE__)) . '/start.php');
elgg_set_ignore_access();

try{
	$db = new DatabaseCall();
	$db->createCF('relationships');
}catch(Exception $e){
	
}

try{
	$db = new DatabaseCall();
	$db->createCF('entities', array('type'=>'UTF8Type'));
}catch(Exception $e){
	
}

$entities = new DatabaseCall('entities');

$site = elgg_get_site_entity();
$entities->insert($site->guid, $site->toArray());

$groups = elgg_get_entities(array('type'=>'group', 'limit'=>0));
foreach($groups as $group){
	$member_guids = $group->member_guids ? unserialize($group->member_guids) : array();
        array_push($member_guids, $group->owner_guid);
	
	foreach($member_guids as $user_guid){ echo $user_guid;
		add_entity_relationship($user_guid, 'member', $group->guid);
	}
}
exit;
foreach(array('object', 'user', 'group', 'notification') as $type){
	$offset = '';
	//copy over objects first
	while(1){
		$objects = elgg_get_entities(array('type'=>$type, 'limit'=>100, 'offset'=>isset($offset)?$offset:''));
		foreach($objects as $object){
			$entities->insert($object->guid, $object->toArray());
			echo "$type:$object->guid migrated \n";
		}
		if(count($objects)<10){
			break;
		}
		$offset = end($objects)->guid;
	}
}
