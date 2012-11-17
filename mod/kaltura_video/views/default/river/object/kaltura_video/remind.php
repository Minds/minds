<?php
/**
 * Wall river remind views
 */

$object = $vars['item']->getObjectEntity();
$excerpt = minds_filter($object->message);

$owner = get_entity($object->owner_guid);

$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$owner_link = elgg_view('output/url', array(
	'href' => $owner->getURL(),
	'text' => $owner->name,
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));

	elgg_load_js('uiVideoInline');
	$image = elgg_view('output/url', array(
		'href' => false,
		'class' => 'uiVideoInline archive',
		'video_id'=> $object->kaltura_video_id,
		'text' =>  '<span></span><img src=\'' . kaltura_get_thumnail($object->kaltura_video_id, 525, 295, 100) . '\' width="525px"/>',
		'title' => $object->title,
	));


$summary = elgg_echo("river:remind:object:kaltura", array($subject_link, $owner_link));


echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $image,
	'summary' => $summary,
));