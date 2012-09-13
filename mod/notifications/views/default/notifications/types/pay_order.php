<?php

$entity = elgg_extract('entity', $vars);
$object = get_entity($entity->object_guid);
$actor = get_entity($object->owner_guid);
if($object instanceof ElggObject){
	//$objectOwner = get_entity($object->getOwnerGUID());
	
	$object_view = elgg_view('output/url', array('href'=>$object->getURL(), 'text'=> $object->guid));

	//Message to show if the user placed the order	
	if($object->owner_guid == elgg_get_logged_in_user_guid()){
			
		$body .= elgg_echo('pay:notification:order', array($object_view));
	
		$body .= "<br/>";
		
		$body .= "<span class='notify_time'>" . elgg_view_friendly_time($entity->time_created) . "</span>";
	} else {
	
		$buyer = elgg_view('output/url', array('href'=>$actor->getURL(), 'text'=>$actor->name));
		
		$body .= elgg_echo('pay:notification:seller:order', array($buyer, $object_view));
		
		$body .= "<br/>";
			
		$body .= "<span class='notify_time'>" . elgg_view_friendly_time($entity->time_created) . "</span>";
	
	}

echo $body;
}
