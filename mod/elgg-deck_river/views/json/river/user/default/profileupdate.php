<?php
/**
 * JSON update profile river view
 */

global $jsonexport;

$subject = $vars['item']->getSubjectEntity();

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$vars['item']->summary = elgg_echo('river:update:user:profile', array($subject_link));

$object = $vars['item']->getObjectEntity();
$vars['item']->message = elgg_get_excerpt($object->description, '140');

$jsonexport['results'][] = $vars['item'];