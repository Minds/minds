<?php

$notification = elgg_extract('entity', $vars);

$from =  minds\core\entities::build(new minds\entities\entity($notification->from_guid));

if(!$from){
	return false;
}

$entity =  minds\core\entities::build(new minds\entities\entity($notification->object_guid));

$body .= elgg_view('output/url', array('href' => $from->getURL(), 'text' => $from->name));
$body .= ' tagged you in a ';
$body .= elgg_view('output/url', array('href' => $entity->getURL(), 'text' => 'post'));
$body .= "<br/>";

$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification-> time_created) . "</span>";

echo $body;
