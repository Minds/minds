<?php
/**
 * Features an item and adds it to elasticsearch
 * 
 */

admin_gatekeeper();
global $CONFIG;

$guid = get_input('guid');
$entity = get_entity($guid, 'object'); //always an object, unless we decide to feature channels...

$es = new elasticsearch();
$es->index = $CONFIG->elasticsearch_prefix.'featured';

if($entity->featured != true){

	$data = new stdClass();
	$data->time_stamp = time();
	
	$es->add($entity->subtype, $entity->getGuid(), json_encode($data));
	
	$entity->featured = 1;
	
	add_to_river('river/object/'.$entity->getSubtype().'/feature', 'feature', $entity->getOwnerGUID(), $entity->getGuid());

	system_message(elgg_echo("Featured..."));
	
	echo 'featured';
	
}else{
	$es->remove($entity->subtype, $entity->getGuid());
	$entity->featured = 0;
	
	minds_elastic_delete_news(array('action_types'=>'feature', 'object_guids'=>array($entity->getGuid())));

	system_message(elgg_echo("Un-featured..."));
	
	echo 'un-featured';

}
$entity->save();
