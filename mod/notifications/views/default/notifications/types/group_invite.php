<?php 
return true;
$entity = elgg_extract('entity', $vars);

$to = get_entity($entity->to_guid);
$actor = get_entity($entity->from_guid);
$group = get_entity($entity->object_guid);

if($group){
$description = $entity->description;

$url = elgg_normalize_url("groups/invitations/$to->username");;

if (strlen($description) > 60){
  $description = substr($entity->description,0,75) . '...' ;
} 

$body .= elgg_view('output/url', array('href'=>$actor->getURL(), 'text'=>$actor->name));
$body .= ' invited you to join the group ';
$body .= elgg_view('output/url', array('href'=>$group->getURL(), 'text'=> $group->name));

$body .= "<br/>";

$body .= "<div class='notify_description'>" .  elgg_view('output/url', array('href'=>$url, 'text'=> 'Join')) . "</div>";

$body .= "<span class='notify_time'>" . elgg_view_friendly_time($entity->time_created) . "</span>";

echo $body;
} else {
	$entity->delete();
}
