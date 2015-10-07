<?php

$notification = elgg_extract('entity', $vars);

$from =  Minds\Core\Entities::build(new Minds\Entities\entity($notification->from_guid));

if(!$from){
	return false;
}

$entity =  Minds\Core\Entities::build(new Minds\Entities\entity($notification->object_guid));
if(!$entity)
    return false;


$body .= elgg_view('output/url', array('href' => $from->getURL(), 'text' => $from->name));
$body .= ' tagged you in a ';
$body .= elgg_view('output/url', array('href' => $entity->getURL(), 'text' => 'post'));
$body .= "<br/>";

$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification-> time_created) . "</span>";

echo $body;
