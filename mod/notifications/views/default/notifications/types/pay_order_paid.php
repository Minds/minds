<?php

$entity = elgg_extract('entity', $vars);

$actor = get_entity($entity->from_guid);
$object = get_entity($entity->object_guid);
if($object){
	//$objectOwner = get_entity($object->getOwnerGUID());
	
	$object = elgg_view('output/url', array('href'=>$object->getURL(), 'text'=> $object->guid));
	
	//Message to show if the user placed the order	
	if($object->getOwnerGUID() == elgg_get_logged_in_user_guid()){
			
		$body .= elgg_echo('pay:notification:order_paid', array($object));
	
		$body .= "<br/>";
		
		$body .= "<span class='notify_time'>" . elgg_view_friendly_time($entity->time_created) . "</span>";
	} else {

		$buyer = elgg_view('output/url', array('href'=>$actor->getURL(), 'text'=>$actor->name));
		
		$body .= elgg_echo('pay:notification:seller:order_paid', array($buyer, $object));
		
		$body .= "<br/>";
			
		$body .= "<span class='notify_time'>" . elgg_view_friendly_time($entity->time_created) . "</span>";
	
	}

echo $body;
}