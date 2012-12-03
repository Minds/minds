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

if ($context) {
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
			'text' => elgg_echo('kalturavideo:label:videoaudio'),
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
		
		//remove other options if on the featured wall
		if(get_input('tab')=='popular'){
			if($name == 'files'){ continue;}
		} elseif(get_input('tab')=='featured') {
			if($name == 'files'){ continue;}
			if($name == 'images'){ continue;}
			if($name == 'media'){ continue;}
			if($name == 'all'){ continue;}
		}
		
		$tab['name'] = $name;
		
		elgg_register_menu_item('filter', $tab);
	}

	echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
}
