<?php
/**
 * View for notifications
 */

$entity = elgg_extract('entity', $vars);

$owner = get_entity($entity->from_guid, 'user');
if($owner){
	$owner_name = $owner->name;
} else {
	$owner_name = "";
}
$date = elgg_view_friendly_time($entity->time_created);

$user = elgg_get_logged_in_user_entity();

$time_created = "<span id=\"timestamp\" class=\"hidden\">$entity->time_created</span>";

$subtitle = "$owner_name $date $time_created";

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'notification',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));


$body = elgg_view('notifications/types/' . $entity->notification_view, array('entity'=>$entity));

$owner_icon = elgg_view_entity_icon($owner, 'small');

$read = $entity->read;

if ($read != 1) {
	// Additional class to notify that message hasn't been read before.
	$vars['class'] = 'notification-unread';
	
	// Mark message read
	$entity->read = 1;
	$entity->save();
}
$object = get_entity($entity->object_guid,'object');

try{
	echo elgg_view_image_block($owner_icon, $body, $vars);
} catch(Exception $e){
}
