<?php
/**
 * Batch river view
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

$image_guids = array_values(json_decode($vars['item']->batch_guids, true));
$num = count($image_guids);

$i = 0;
foreach($image_guids as $image_guid){
	if($i == 3)
		continue;
	$images = elgg_view('output/img', array('src'=> elgg_get_site_url() . 'archive/thumbnail/'.$image_guid.'/medium'));
	$body .= elgg_view('output/url', array(
		'href'=> $album->getURL() . '/'.$image_guid, 
		'text'=> $images,
		'class' => 'image-thumbnail lightbox-image batch-thumbnails',
		'data-album-guid'=>$album->guid
	));
	$i++;
}

$summary = elgg_echo("river:remind:object:batch", array($subject_link, $vars['item']->batch_count, $album_link));


echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $body,
	'summary' => $summary,
));
