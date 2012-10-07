<?php
/**
 * Main content filter
 *
 * Select between user, friends, and all content
 *
 * @uses $vars['filter_context']  Filter context: all, friends, mine
 * @uses $vars['filter_override'] HTML for overriding the default filter (override)
 * @uses $vars['context']         Page context (override)
 */


$context = elgg_extract('context', $vars, elgg_get_context());

if (elgg_is_logged_in() && $context) {
	$username = elgg_get_logged_in_user_entity()->username;
	$filter_context = get_input('filter', 'all');

	$tabs = array(
		'all' => array(
			'text' => elgg_echo('all'),
			'href' => '?filter=all',
			'selected' => ($filter_context == 'all'),
			'priority' => 0,
		),
		'media' => array(
			'text' => elgg_echo('kalturavideo:label:latest'),
			'href' => '?filter=media',
			'selected' => ($filter_context == 'media'),
			'priority' => 100,
		),
		'images' => array(
			'text' => elgg_echo('photos'),
			'href' => '?filter=images',
			'selected' => ($filter_context == 'images'),
			'priority' => 200,
		),
		'files' => array(
			'text' => elgg_echo('file'),
			'href' => '?filter=files',
			'selected' => ($filter_context == 'files'),
			'priority' => 300,
		),

	);
	
	foreach ($tabs as $name => $tab) {
		$tab['name'] = $name;
		
		elgg_register_menu_item('filter', $tab);
	}

	echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
}
