<?php
/**
 * Webinar river view.
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);

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


echo elgg_view('river/elements/layout', array(
	'summary'=> elgg_echo("river:create:object:webinar", array($subject_link, $object_link)),
	'item' => $vars['item'],
	'message' => $excerpt,
));
