<?php
/**
 * Mark an item to monetize
 */
admin_gatekeeper();

$guid = get_input('guid');
$entity = get_entity($guid);

if($entity->monetized == true){
	$entity->monetized = false;
}else {
	$entity->monetized = true;
}
$entity->save();
