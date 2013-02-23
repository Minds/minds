<?php
/**
 * Blog river view.
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->excerpt);
if (!$excerpt) {
	$excerpt = elgg_get_excerpt($object->description);
}

$image = elgg_view('output/img', array('src'=>minds_fetch_image($object->description, $object->owner_guid), 'class'=>'rich-image'));
$img_link = '<div class="rich-image-container">' . elgg_view('output/url', array('href'=>$object->getURL(), 'text'=>$image)) . '</div>';
$readmore = elgg_view('output/url', array('href'=>$object->getURL(), 'text'=>elgg_echo('readmore'), 'class'=>'readmore'));

$body = elgg_view_image_block($img_link, $excerpt . $readmore, array('class'=>'rich-content news'));

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $body,
));