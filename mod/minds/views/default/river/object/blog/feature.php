<?php
/**
 * Blog remind
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->excerpt);
if (!$excerpt) {
	$excerpt = elgg_get_excerpt($object->description);
}

$image = elgg_view('output/img', array('src'=>minds_fetch_image($object->description, $object->owner_guid), 'class'=>'rich-image'));
$img_link = '<div class="rich-image-container">' . elgg_view('output/url', array('href'=>$object->getURL(), 'text'=>$image)) . '</div>';
$readmore = elgg_view('output/url', array('href'=>$object->getURL(), 'text'=>elgg_echo('readmore'), 'class'=>'readmore'));

$content = elgg_view('output/url', array('href'=>$object->getURL(), 'text' => elgg_view_title($object->title))).$excerpt . $readmore;
$body = elgg_view_image_block($img_link, $content, array('class'=>'rich-content news'));

$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$owner_link = elgg_view('output/url', array(
	'href' => $object->getOwnerEntity()->getURL(),
	'text' => $object->getOwnerEntity()->name,
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));

$blog_link = elgg_view('output/url', array(
	'href' => $object->getURL(),
	'text' => $object->title,
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $body,
	'summary' => elgg_echo("river:feature:object:blog", array($subject_link, $blog_link)),
));
