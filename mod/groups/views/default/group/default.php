<?php 
/**
 * Group entity view
 * 
 * @package ElggGroups
 */

$group = $vars['entity'];

$icon = elgg_view_entity_icon($group, 'large');

$metadata = elgg_view_menu('entity', array(
	'entity' => $group,
	'handler' => 'groups',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

if (elgg_in_context('owner_block') || elgg_in_context('widgets')) {
	$metadata = '';
}


if ($vars['full_view']) {
	echo elgg_view('groups/profile/summary', $vars);
} else {
	// brief view
	$icon = elgg_view_entity_icon($group, 'small');
	$params = array(
		'entity' => $group,
		'metadata' => $metadata,
		'subtitle' => $group->briefdescription,
	);
	$params = $params + $vars;
	$list_body = elgg_view('group/elements/summary', $params);
	
	$title = elgg_view('output/url', array('text'=>elgg_view_title($group->name), 'href'=>$group->getURL()));
	$members = elgg_view_entity_list($group->getMembers(6), array('list_type' => 'gallery',
                'gallery_class' => 'elgg-gallery-users', 'pagination'=>false));
	$content = $title . $members;

	echo elgg_view_image_block($icon, $content, $vars);
}
