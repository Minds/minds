<?php
/**
 * JSON update avatar river view
 */

global $jsonexport;

$subject = $vars['item']->getSubjectEntity();

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$vars['item']->summary = elgg_echo('river:update:user:avatar', array($subject_link));

$vars['item']->message = elgg_view_entity_icon($subject, 'tiny', array(
	'use_hover' => false,
	'use_link' => false,
));

$jsonexport['results'][] = $vars['item'];