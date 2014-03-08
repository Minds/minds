<?php
/**
 * Wall river views
 */

$object = $vars['item']->getObjectEntity();
$excerpt = minds_filter($object->message);
$to = get_entity($object->to_guid, 'user');
//bad way of doing this, but if not a user, try a group
if(!$to){
	$to = get_entity($object->to_guid, 'group');
}
//var_dump($to);
$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$owner_link = elgg_view('output/url', array(
	'href' => elgg_instanceof($to, 'group') ? "wall/group/$to->guid" : "wall/$to->username",
	'text' => $to->name,
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));

if($object->owner_guid == $object->to_guid || $to instanceof ElggGroup || !$to){
	$summary = elgg_echo("river:create:object:wall", array($subject_link));
} else {
	$summary = elgg_echo("river:create:object:wall", array($subject_link, $owner_link));
}

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => '<p>' . $excerpt . '</p>',
	'summary' => $summary,
));
