<?php
/**
 * External search remind view
 */

$object = $vars['item']->getObjectEntity();//this is actualy the item_id
$excerpt = minds_filter($object->message);
$message = $object->message;

$source = elgg_view('output/url', array('href'=>$object->source_href, 'text'=>$object->source));

$img_url = $object->img_url;

if($object->source=='flickr'){
	$img_url = str_replace('_q', '_c', $img_url);
	$img = elgg_view('output/img', array('src'=>$img_url, 'width'=>515));
	$output = elgg_view('output/url', array('href'=>elgg_get_site_url().'search/result/'.$object->id, 'text'=>$img));
}elseif($object->source=='youtube'){
	$yt_id = str_replace('youtube_', '', $object->id);
	$output= '<iframe src="http://youtube.com/embed/'.$yt_id.'" width="515px" height="275px"></iframe>';
}elseif($object->source=='freesound'){
	$fs_id = str_replace('freesound_', '', $object->id);
	$output= '<iframe src="http://www.freesound.org/embed/sound/iframe/'.$fs_id.'/simple/medium" width="515px" height="100px"></iframe>';
}elseif($object->source=='soundcloud'){
	$sc_id = str_replace('soundcloud_', '', $object->id);
	$output= '<iframe width="515px" height="175px" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F'.$sc_id.'"></iframe>';
}

$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

	
$object_link = elgg_view('output/url', array(
	'href' => $object->getURL(),
	'text' => $object->title,
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));


$summary = elgg_echo("river:remind:external:search", array($subject_link, $object->title, $source));

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $output,
	'summary' => $summary,
));