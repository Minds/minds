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
	
    echo elgg_view('icon/group/hovercard', array('group'=>$group, 'show'=>true));
    return true;
}
