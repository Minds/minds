<?php

$notification = elgg_extract('entity', $vars);
$params = unserialize($notification->params);
$type = $params['type'] ? $params['type'] : 'entity';


$entity =  Minds\Core\entities::build(new minds\entities\entity($notification->object_guid));
if(!$entity || !$entity->title)
	return false;
		
$body .= elgg_view('output/url', array('href'=>$entity->getURL(), 'text'=> $entity->title));
$body .= ' has been Featured!';
$body .= "<br/>";
	
$body .= "<div class='notify_description'>" .  $description . "</div>";

$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification-> time_created) . "</span>";

echo $body;
