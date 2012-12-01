<?php

$entity = elgg_extract('entity', $vars);

$actor = get_entity($entity->from_guid);
$object = get_entity($entity->object_guid);
if($object){
	//$objectOwner = get_entity($object->getOwnerGUID());
	$subtype = $object->getSubtype();
	if($subtype == 'thewire' && $entity->to_guid == $object->getOwnerGUID()){
		$object_title = 'your post';
	} elseif($subtype == 'thewire') {
		if($entity->from_guid == $object->getOwnerGUID()){
			$object_title  = 'their own post';
		} else {
		$object_title = $objectOwner->name . '\'s post';
		}
	}elseif($subtype == 'wallpost'){
		$object_title = 'a wall post';
	}else {
		$object_title = $object->title;
	}
	
	$description = $entity->description;
	if (strlen($description) > 60){
	  $description = substr($entity->description,0,75) . '...' ;
	} 
	
	$body .= elgg_view('output/url', array('href'=>$actor->getURL(), 'text'=>$actor->name));
	$body .= $object_title == '' ? elgg_view('output/url', array('href'=>elgg_get_site_url() . 'news/single?id=' . $entity->object_guid, 'text'=> ' commented')) : ' commented on ';
	$body .= elgg_view('output/url', array('href'=>$object->getURL(), 'text'=> $object_title));
	
	$body .= "<br/>";
	
	$body .= "<div class='notify_description'>" .  $description . "</div>";
	
	$body .= "<span class='notify_time'>" . elgg_view_friendly_time($entity->time_created) . "</span>";
	
	echo $body;

}
