<?php
/**
 * Boost submitted
 */
$notification = elgg_extract('entity', $vars);

    
$entity =  Minds\Entities\Factory::build($notification->object_guid);
if (!$entity) {
    echo "Sorry, we could not this notification";
    return true;
} 

$from = Minds\Entities\Factory::build($notification->from_guid);

$points = (int) $notification->params['points'];

$body = "@$from->username is offering your $points to boost";

if($entity->title)
    $body .= elgg_view('output/url', array('href' => $entity->getURL(), 'text' => $entity->title));
else
    $body .= elgg_view('output/url', array('href' => $entity->getURL(), 'text' =>"their post"));


$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification-> time_created) . "</span>";

echo $body;
