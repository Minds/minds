<?php
/**
 * Features an item and adds it to elasticsearch
 * 
 */

admin_gatekeeper();

$guid = get_input('guid');
$entity = get_entity($guid);

$es = new elasticsearch();
$es->index = 'featured';

if($entity->featured != true){

	$data = new stdClass();
	$data->time_stamp = time();
	
	$es->add($entity->getSubType(), $entity->getGuid(), json_encode($data));
	
	$entity->featured = true;
	
}else{
	$es->remove($entity->getSubType(), $entity->getGuid());
	$entity->featured = false;
}

$entity->save();
