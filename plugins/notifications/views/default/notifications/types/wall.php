<?php 

$entity = elgg_extract('entity', $vars);

$object = get_entity($entity->object_guid);

if($object){
	
	$actor = get_entity($entity->from_guid);
	
	$to = get_entity($entity->to_guid);
	
	
	$description = $entity->description;
	if (strlen($description) > 60){
	  $description = substr($entity->description,0,75) . '...' ;
	} 
	
	$body .= elgg_view('output/url', array('href'=>$actor->getURL(), 'text'=>$actor->name));
	$body .= ' has posted on your ';
	$body .= elgg_view('output/url', array('href'=>$object->getURL(), 'text'=> 'your wall'));
	
	$body .= "<br/>";
	
	$body .= "<div class='notify_description'>" .  $description . "</div>";
	
	$body .= "<span class='notify_time'>" . elgg_view_friendly_time($entity->time_created) . "</span>";
	
	echo $body;
}