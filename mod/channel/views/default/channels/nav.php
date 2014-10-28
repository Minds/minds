<?php
/**
 * Members navigation
 */

$tabs = array(
	'featured' => array(
		'text' => elgg_echo('channels:label:featured'),
         'href' => "channels/featured",
         'selected' => $vars['selected'] == 'featured',
        ),
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
	'subscribe' => array(
		'text' => elgg_echo('Subscribe'),
		'href' => "subscriptions/add",
		'selected' => $vars['selected'] == 'subscribe',
	),
	'newest' => array(
		'text' => elgg_echo('channels:label:newest'),
		'href' => "channels/newest",
		'selected' => $vars['selected'] == 'newest',
	),
);

if(!elgg_is_logged_in()){
	unset($tabs['subscribers']);
	unset($tabs['subscriptions']);
}

if(!minds\core\plugins::isActive('analytics'))
	unset($tabs['trending']);

//echo elgg_view('navigation/tabs', array('tabs' => $tabs));
foreach ($tabs as $name => $tab) {
		
	//remove other options if on the featured wall
				
	$tab['name'] = $name;
		
	elgg_register_menu_item('filter', $tab);
}

echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
