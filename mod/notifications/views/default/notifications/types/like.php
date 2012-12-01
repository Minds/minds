<?php

$entity = elgg_extract('entity', $vars);

$actor = get_entity($entity->from_guid);
$object = get_entity($entity->object_guid);
if($object){
$subtype = $object->getSubtype();
if($subtype == 'thewire'){
	$object_title = 'your post';
}elseif($subtype == 'wallpost'){
		$object_title = 'your thought';
} elseif($subtype == 'hjannotation') {
	$object = get_entity($object->parent_guid);
	$object_title = ' your comment';
}elseif($subtype == 'image'){
	$object_title = $object->getTitle();
}else{
	$object_title = $object->title;
}

$description = $entity->description;
if (strlen($description) > 60){
  $description = substr($entity->description,0,75) . '...' ;
} 

$body .= elgg_view('output/url', array('href'=>$actor->getURL(), 'text'=>$actor->name));
$body .= ' has voted up ';
$body .= elgg_view('output/url', array('href'=>$object->getURL(), 'text'=> $object_title));

$body .= "<br/>";

$body .= "<div class='notify_description'>" .  $description . "</div>";

$body .= "<span class='notify_time'>" . elgg_view_friendly_time($entity->time_created) . "</span>";

echo $body;
}
