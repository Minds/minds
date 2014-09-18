<?php

$activity = $vars['entity'];

$owner_link = '';
$owner = $activity->getOwnerEntity();
if ($owner) {
	$owner_link = elgg_view('output/url', array(
		'href' => $owner->getURL(),
		'text' => $owner->name . '<span class="username"> @'.$owner->username.'</span>',
		'is_trusted' => true,
	));
}
$icon = elgg_view_entity_icon($owner, 'small');

$date = elgg_view_friendly_time($vars['entity']->time_created);

$subtitle = "$owner_link $date";

$header = "<div class=\"head\">$owner_link &bull; $date</div>";

$body = "";

if($activity->message)
	$body .= elgg_view('activity/elements/message', array('message'=>$activity->message));

/**
 * This is an rich embed
 */
if($activity->title){
	$body .= elgg_view('activity/elements/rich', array('activity'=>$activity)); 
}

echo elgg_view_image_block($icon, $header . $body, $vars);
echo elgg_view_comments($activity);

echo elgg_view_menu('entity', array(
    'entity' => $activity,
    'handler' => 'activity',
    'sort_by' => 'priority',
    'class' => 'menu-activity',
    'full_view' => $full
));