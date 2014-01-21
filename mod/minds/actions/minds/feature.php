<?php
/**
 * Features an item and adds it to elasticsearch
 * 
 */

admin_gatekeeper();
global $CONFIG;

$guid = get_input('guid');
$entity = get_entity($guid, 'object'); //always an object, unless we decide to feature channels...

if(!$entity->featured || $entity->featured == 0){

	$entity->feature();

	add_to_river('river/object/'.$entity->getSubtype().'/feature', 'feature', $entity->getOwnerGUID(), $entity->getGuid());

	system_message(elgg_echo("Featured..."));
	
	echo 'featured';

	//Send notification Chris

	$to_guid = $entity->getOwnerGuid();
	$user = get_user_by_username('minds');

	notification_create(array($to_guid), $user, $guid, array('description'=>$message,'notification_view'=>'feature'));
	
}else{

	$entity->unFeature();
	
	system_message(elgg_echo("Un-featured..."));
	
	echo 'un-featured';

}
$entity->save();

