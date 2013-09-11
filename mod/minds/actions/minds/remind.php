<?php
/**
 * Minds ReMind
 * 
 * @author Mark Harding (mark@minds.com)
 * 
 */

gatekeeper();

$guid = get_input('guid');
$entity = get_entity($guid);

if($entity instanceof ElggEntity){

} else {
	forward();
	return false;
}

$subtype = $entity->getSubtype();
if($subtype == 'wallpost'){
	$subtype = 'wall';
}

add_to_river('river/object/' . $subtype . '/remind', 'remind', elgg_get_logged_in_user_guid(), $guid);
add_entity_relationship($guid, 'remind', elgg_get_logged_in_user_guid()); 

system_message(elgg_echo("minds:remind:success"));

//Chris set use settings
elgg_set_plugin_user_setting('reminded', true, elgg_get_logged_in_user_guid(), 'minds');

forward(REFERRER);