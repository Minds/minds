<?php 
$notification = elgg_extract('entity', $vars);
$params = unserialize($notification->params);
$type = $params['type'] ? $params['type'] : 'entity';

$actor = get_entity($notification->from_guid,'user');

$description = $notification->description;
	if (strlen($description) > 60){
	  $description = substr($notification->description,0,75) . '...' ;
    }
	$body .= elgg_view('output/url', array('href'=>$actor->getURL(), 'text'=>$actor->name));
	$body .= ' subscribed to you';
	
	$body .= "<br/>";
	
	$body .= "<div class='notify_description'>" .  $description . "</div>";
	
	$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification->time_created) . "</span>";
	
	echo $body;
