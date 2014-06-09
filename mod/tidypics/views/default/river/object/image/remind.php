<?php
/**
 * Remind views
 */

$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$image = $vars['item']->getObjectEntity();
$attachments = elgg_view_entity_icon($image, 'large',array('img_class'=>'large'));

$image_link = elgg_view('output/url', array(
	'href' => $image->getURL(),
	'text' => $image->getTitle(),
	'is_trusted' => true,
));
if($image->getContainerEntity()){
	$album_link = elgg_view('output/url', array(
		'href' => $image->getContainerEntity()->getURL(),
		'text' => $image->getContainerEntity()->getTitle(),
		'is_trusted' => true,
	));
}

$object = $vars['item']->getObjectEntity();
$owner = $object->getOwnerEntity();
$owner_link = elgg_view('output/url', array(
	'href' => $owner->getURL(),
	'text' => $owner->name,
	'class' => 'elgg-river-owner',
	'is_trusted' => true,
));

$object_link = elgg_view('output/url', array(
	'href' => $image->getURL(),
	'text' => $image->getTitle(),
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));


$summary = elgg_echo("river:remind:object:image", array($subject_link, $owner_link, $object_link));


echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'attachments' => $attachments,
	'summary' => $summary,
));
