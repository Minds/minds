<?php
/**
 * Wall river remind views
 */

$album = $vars['item']->getObjectEntity();

$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));


$album_link = elgg_view('output/url', array(
	'href' => $album->getURL(),
	'text' => $album->title,
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));

elgg_load_js('popup');
$body = elgg_view('output/url', array(
		'href'=> $album->getURL(), 
		'text'=> elgg_view('output/img', array('src'=>$album->getIconURL('large'))),
		'class' => 'image-thumbnail lightbox-image',
		'data-album-guid'=>$album->guid
	));


$summary = elgg_echo("river:remind:object:album", array($subject_link, $album_link));


echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $body,
	'summary' => $summary,
));
