<?php
/**
 * Boost submitted
 */
$notification = elgg_extract('entity', $vars);
	
$entity =  Minds\entities\Factory::build($notification->object_guid);
if (!$entity) {
	echo "Sorry, we could not this notification";
    return true;
} 

$impressions = (int) $notification->params['impressions'];

$body = "$impressions views for ";

if($entity->title)
    $body .= elgg_view('output/url', array('href' => $entity->getURL(), 'text' => $entity->title));
else
    $body .= elgg_view('output/url', array('href' => $entity->getURL(), 'text' =>"your post"));

$body .= " were accepted.";

$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification-> time_created) . "</span>";

echo $body;
