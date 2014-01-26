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

foreach(array('object', 'user', 'group', 'notification') as $type){
	//copy over objects first
	while(1){
		$objects = elgg_get_entities(array('type'=>$type, 'limit'=>100, 'offset'=>isset($offset)?$offset:''));
		foreach($objects as $object){
			$entities->insert($object->guid, $object->toArray());
			echo "$type:$object->guid migrated \n";
		}
		if(count($objects)<100){
			break;
		}
		$offset = end($objects)->guid;
	}
}