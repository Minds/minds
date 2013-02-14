<?php
/**
 * Remind view for API reminds
 */

$object = $vars['item']->getObjectEntity();
$excerpt = minds_filter($object->description);
$description = $object->description;

$site = elgg_extract('site', $vars);

$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$title = elgg_view('output/url', array('href'=>$object->href, 'text'=>$object->title));
$image = elgg_view('output/img', array('src'=>minds_fetch_image($description), 'class'=>'rich-image'));
$img_link = '<div class="rich-image-container">' . elgg_view('output/url', array('href'=>$object->href, 'text'=>$image)) . '</div>';
$readmore = elgg_view('output/url', array('href'=>$object->href, 'text'=>elgg_echo('readmore'), 'class'=>'readmore'));

$body = elgg_view_image_block($img_link, $excerpt . $readmore, array('class'=>'rich-content news'));

$summary = elgg_echo("river:remind:api", array($subject_link, $title));

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $body,
	'summary' => $summary,
));