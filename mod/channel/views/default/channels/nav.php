<?php
/**
 * Members navigation
 */

$tabs = array(
	'trending' => array(
		'text' => elgg_echo('channels:label:trending') . elgg_view_menu('trending'),
         'href' => "channels/trending",
         'selected' => $vars['selected'] == 'trending',
         'item_class'=>'elgg-menu-item-hover-over'
        ),
	'subscribers' => array(
		'text' => elgg_echo('channels:label:subscribers'),
		'href' => "channels/subscribers",
		'selected' => $vars['selected'] == 'subscribers',
	),
	'subscriptions' => array(
		'text' => elgg_echo('channels:label:subscriptions'),
		'href' => "channels/subscriptions",
		'selected' => $vars['selected'] == 'subscriptions',
	),
	'newest' => array(
		'text' => elgg_echo('channels:label:newest'),
		'href' => "channels/newest",
		'selected' => $vars['selected'] == 'newest',
	),
/*	'popular' => array(
		'text' => elgg_echo('channels:label:popular'),
		'href' => "channels/popular",
		'selected' => $vars['selected'] == 'popular',
	),
	'suggested' => array(
		'text' => elgg_echo('channels:label:suggested'),
		'href' => "channels/suggested",
		'selected' => $vars['selected'] == 'suggested',
	),
	'online' => array(
		'text' => elgg_echo('channels:label:online'),
		'href' => "channels/online",
		'selected' => $vars['selected'] == 'online',
	),
	'online' => array(
		'text' => elgg_echo('friends:collections'),
		'href' => "channels/collections",
		'selected' => $vars['selected'] == 'collections',
	),*/
);

//echo elgg_view('navigation/tabs', array('tabs' => $tabs));
foreach ($tabs as $name => $tab) {
		
	//remove other options if on the featured wall
				
	$tab['name'] = $name;
		
	elgg_register_menu_item('filter', $tab);
}

echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
