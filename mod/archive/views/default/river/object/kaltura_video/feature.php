<?php
/**
 * Wall river remind views
 */

$object = $vars['item']->getObjectEntity();
$excerpt = minds_filter($object->message);


$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));


	elgg_load_js('uiVideoInline');
	$image = elgg_view('output/url', array(
		'href' => false,
		'class' => 'uiVideoInline archive',
		'video_id'=> $object->kaltura_video_id,
		'text' =>  '<span></span><img src=\'' . kaltura_get_thumnail($object->kaltura_video_id, 515, 290, 100, $object->thumbnail_sec) . '\'/>',
		'title' => $object->title,
	));
	
$object_link = elgg_view('output/url', array(
	'href' => $object->getURL(),
	'text' => $object->title,
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));


$summary = elgg_echo("river:feature:object:kaltura", array($subject_link, $object_link));


echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $image,
	'summary' => $summary,
));
