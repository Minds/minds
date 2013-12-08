<?php
/**
 * JSON thewire river view
 *
 * @uses $vars['item']
 */

global $jsonexport;

$mention = elgg_extract('mention', $vars, false);

$subject = $vars['item']->getSubjectEntity();
$object = $vars['item']->getObjectEntity();

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));
$object_link = elgg_view('output/url', array(
	'href' => $object->getURL(),
	'text' => elgg_echo('thewire:wire'),
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));

$vars['item']->summary = elgg_echo("river:create:object:thewire", array($subject_link, $object_link));

$excerpt = strip_tags($object->description);

if ($mention) $excerpt = deck_river_highlight_mention($excerpt, $mention);

if ($object->reply) $vars['item']->responses = $object->wire_thread;

if ($object->method) $vars['item']->method = $object->method;

$vars['item']->message = $excerpt;

$jsonexport['results'][] = $vars['item'];

