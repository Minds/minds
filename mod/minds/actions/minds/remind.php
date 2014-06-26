<?php
/**
 * Minds ReMind
 * 
 * @author Mark Harding (mark@minds.com)
 * 
 */

gatekeeper();

$guid = get_input('guid');
$entity = get_entity($guid,'object');

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
//add_entity_relationship($guid, 'remind', elgg_get_logged_in_user_guid()); 

system_message(elgg_echo("minds:remind:success"));

//Send notification Chris

$to_guid = $entity->getOwnerGuid();
$from_guid = elgg_get_logged_in_user_guid();
 
\elgg_trigger_plugin_hook('notification', 'all', array(
		'to' => array($to_guid),
		'object_guid'=>$from_guid,
		'description'=>$message,
		'notification_view'=>'remind'
	));

//Chris set use settings
elgg_set_plugin_user_setting('reminded', true, elgg_get_logged_in_user_guid(), 'minds');

forward(REFERRER);
