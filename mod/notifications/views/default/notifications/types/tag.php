<?php

$notification = elgg_extract('entity', $vars);
$actor = $notification->getOwnerEntity();

$description = htmlspecialchars($notification->description, ENQ_QUOTES);
if (strlen($description) > 60) {
	$description = substr($notification -> description, 0, 75) . '...';
}

$body .= elgg_view('output/url', array('href' => $actor -> getURL(), 'text' => $actor -> name));
$body .= ' tagged you in a ';
$body .= elgg_view('output/url', array('href' => elgg_get_site_url() . 'newsfeed/'.$notification->object_guid, 'text' => 'post'));
$body .= "<br/>";

$body .= "<div class='notify_description'>" . $description . "</div>";

$body .= "<span class='notify_time'>" . elgg_view_friendly_time($notification-> time_created) . "</span>";

echo $body;
