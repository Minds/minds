<?php
/**
 * Mark an item as featured
 */
admin_gatekeeper();

$guid = get_input('guid');
$entity = get_entity($guid);

if($entity->featured == true){
	$entity->featured = false;
}else {
	$entity->featured = true;
}
$entity->save();
