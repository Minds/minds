<?php
/**
 * Features an item and adds it to elasticsearch
 * 
 */

admin_gatekeeper();
global $CONFIG;

$guid = get_input('guid');
$entity = get_entity($guid, 'object'); //always an object, unless we decide to feature channels...

if($entity->featured != true){

	
	db_insert('object:featured', array('type'=>'entities_by_time',$entity->getGuid() => time()));
	db_insert('object:'.$entity->subtype.':featured', array('type'=>'entities_by_time',$entity->getGuid() => time()));
	
	$entity->featured = 1;
	
	add_to_river('river/object/'.$entity->getSubtype().'/feature', 'feature', $entity->getOwnerGUID(), $entity->getGuid());

	system_message(elgg_echo("Featured..."));
	
	echo 'featured';
	
}else{

	db_remove('object:featured', 'entities_by_time', array($entity->getGuid()));
	db_remove('object:'.$entity->subtype.':featured','entities_by_time', array($entity->getGuid())); 

	$entity->featured = 0;
	
	system_message(elgg_echo("Un-featured..."));
	
	echo 'un-featured';

}
$entity->save();

//Send notification Chris

$to_guid = $entity->getOwnerGuid();
$user = get_user_by_username('minds');
 
notification_create(array($to_guid), $user, $guid, array('description'=>$message,'notification_view'=>'feature'));
