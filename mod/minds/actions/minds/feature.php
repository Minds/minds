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
	
	$es->add($entity->type, $entity->guid, json_encode($data));
	
	$entity->featured = true;
	
}else{
	$es->remove($es->type, $entity->guid);
	$entity->featured = false;
}

$entity->save();
