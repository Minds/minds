<?php
/**
 * Short summary of the action that occurred
 *
 * @vars['item'] ElggRiverItem
 * @vars['hash'] String     default false    Should we add hash. eg: http://ggouv.fr/object/view/543/title#item-annotation-853
 */

$item = $vars['item'];

$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();

if (!$object) return;
$container = $object->getContainerEntity();

$subject_link = elgg_view('output/url', array(
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
));

$hash = ($vars['hash'] && $vars['hash'] != false) ? $vars['hash'] : '';
$object_link = elgg_view('output/url', array(
	'href' => $object->getURL() . $hash,
	'text' => $object->title ? $object->title : $object->name,
	'class' => 'elgg-river-object',
	'is_trusted' => true,
));

$action = $item->action_type;
$type = $item->type;
$subtype = $item->subtype ? $item->subtype : 'default';

$group_string = '';
if ($container instanceof ElggGroup && $container->guid != elgg_get_page_owner_guid()) {
	$params = array(
		'href' => $container->getURL(),
		'text' => $container->name,
		'is_trusted' => true,
	);
	$group_link = elgg_view('output/url', $params);

	$key = "river:ingroup:$action:$type:$subtype";
	$group_string = elgg_echo($key, array($group_link));
	if ($group_string == $key) $group_string = elgg_echo('river:ingroup', array($group_link));
}

// check summary translation keys.
// will use the $type:$subtype if that's defined, otherwise just uses $type:default
$key = "river:$action:$type:$subtype";
$summary = elgg_echo($key, array($subject_link, $object_link));
if ($summary == $key) {
	$key = "river:$action:$type:default";
	$summary = elgg_echo($key, array($subject_link, $object_link));
}

echo $summary . '&nbsp;' . $group_string;