<?php

$activity = $vars['entity'];

$owner_link = '';
$owner = $activity->getOwnerEntity(true);
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

$header = "<div class=\"head\">$owner_link $date</div>";

$body = "";

if($activity->message)
	$body .= elgg_view('activity/elements/message', array('message'=>$activity->message));


if($activity->remind_object){
	$body .= elgg_view('activity/elements/remind', array('remind'=>$activity->remind_object));
}

if($activity->custom_type){
	if(elgg_view_exists('activity/elements/'.$activity->custom_type))
		$body .= elgg_view('activity/elements/'.$activity->custom_type, array('data'=>$activity->custom_data));
	else
		$body .= elgg_view('activity/elements/custom', array('type'=>$activity->custom_type,'data'=>$activity->custom_data, 'title'=>$activity->title));
}

/**
 * This is an rich embed
  */
if($activity->title){
    $body .= elgg_view('activity/elements/rich', array('activity'=>$activity)); 
 }

echo elgg_view_image_block($icon, $header . $body, array(
		'class' => 'inner'
	));

//a bit of a hack until we remove old style elgg views
if($activity->entity_guid){
	$obj = new \Minds\entities\entity();
	$obj->guid = $activity->entity_guid;
	echo elgg_view_comments($obj);
} else {	
	echo elgg_view_comments($activity);
}

//assumes true	
if(isset($vars['menu']) && $vars['menu'] || !isset($vars['menu']))
	echo elgg_view_menu('entity', array(
	    'entity' => $activity,
	    'handler' => 'activity',
	    'sort_by' => 'priority',
	    'class' => 'menu-activity',
	    'full_view' => $full
	));
