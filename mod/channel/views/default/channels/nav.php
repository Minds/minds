<?php
/**
 * Members navigation
 */

$tabs = array(
	'trending' => array(
		'title' => elgg_echo('channels:label:trending'),
                'url' => "channels/trending",
                'selected' => $vars['selected'] == 'trending',
        ),
	'subscribers' => array(
		'title' => elgg_echo('channels:label:subscribers'),
		'url' => "channels/subscribers",
		'selected' => $vars['selected'] == 'subscribers',
	),
	'subscriptions' => array(
		'title' => elgg_echo('channels:label:subscriptions'),
		'url' => "channels/subscriptions",
		'selected' => $vars['selected'] == 'subscriptions',
	),
	'newest' => array(
		'title' => elgg_echo('channels:label:newest'),
		'url' => "channels/newest",
		'selected' => $vars['selected'] == 'newest',
	),
/*	'popular' => array(
		'title' => elgg_echo('channels:label:popular'),
		'url' => "channels/popular",
		'selected' => $vars['selected'] == 'popular',
	),
	'suggested' => array(
		'title' => elgg_echo('channels:label:suggested'),
		'url' => "channels/suggested",
		'selected' => $vars['selected'] == 'suggested',
	),
	'online' => array(
		'title' => elgg_echo('channels:label:online'),
		'url' => "channels/online",
		'selected' => $vars['selected'] == 'online',
	),
	'online' => array(
		'title' => elgg_echo('friends:collections'),
		'url' => "channels/collections",
		'selected' => $vars['selected'] == 'collections',
	),*/
);

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
