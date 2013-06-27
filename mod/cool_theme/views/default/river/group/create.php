<?php
/**
 * Group creation river view.
 */

$object = $vars['item']->getObjectEntity();
$subject = $vars['item']->getSubjectEntity();

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-actor-name',
	'encode_text' => true,
));

$group_link = elgg_view('output/url', array(
	'href' => $object->getURL(),
	'text' => $object->name,
	'encode_text' => true,
));

echo elgg_view('river/item', array(
	'item' => $vars['item'],
	'attachments' => elgg_view('group/default/river', array('entity' => $object)),
));