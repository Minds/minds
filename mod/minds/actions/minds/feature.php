<?php
/**
 * Features an item and adds it to elasticsearch
 * 
 */

admin_gatekeeper();
global $CONFIG;

$guid = get_input('guid');
$entity = get_entity($guid, 'object'); //always an object, unless we decide to feature channels...

if(!$entity->featured_id || $entity->featured_id == 0){

	$entity->feature();

	add_to_river('river/object/'.$entity->getSubtype().'/feature', 'feature', $entity->getOwnerGUID(), $entity->getGuid(), 2, time(), NULL, array('feature'));

	system_message(elgg_echo("Featured..."));
	
	echo 'featured';

	//Send notification Chris

	$to_guid = $entity->getOwnerGuid();
	$user = get_user_by_username('minds');

	\elgg_trigger_plugin_hook('notification', 'all', array(
				'to' => array($to_guid),
				'object_guid'=>$guid,
				'description'=>$message,
				'notification_view'=>'feature'
			));
}else{

	$entity->unFeature();
	
	system_message(elgg_echo("Un-featured..."));
	
	echo 'un-featured';

}
$entity->save();

