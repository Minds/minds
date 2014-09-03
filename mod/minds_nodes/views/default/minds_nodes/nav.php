<?php
/**
 * Nodes filter
 */

$tabs = array(
	'mine' => array(
		'text' => elgg_echo('nodes:mine'),
		'href' => "nodes/manage/mine",
		'selected' => $vars['selected'] == 'mine',
	),
	'referred' => array(
		'text' => elgg_echo('nodes:referred'),
		'href' => "nodes/manage/referred",
		'selected' => $vars['selected'] == 'referred',
	),
);

//echo elgg_view('navigation/tabs', array('tabs' => $tabs));
foreach ($tabs as $name => $tab) {
		
	//remove other options if on the featured wall
				
	$tab['name'] = $name;
		
	elgg_register_menu_item('filter', $tab);
}

echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
