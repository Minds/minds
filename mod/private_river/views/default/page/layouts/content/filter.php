<?php
/**
 * Main content filter
 *
 * Select between user, friends, and (all if admin) content 
 * 
 * @uses $vars['filter_context']  Filter context: all, friends, mine for horizontal tab
 * @uses $vars['filter_override'] HTML for overriding the default filter (override)
 * @uses $vars['context']         Page context (override)
 */
if (elgg_is_admin_logged_in()) {
if (isset($vars['filter_override'])) {
	echo $vars['filter_override'];
	return true;
}

$context = elgg_extract('context', $vars, elgg_get_context());
if (elgg_is_logged_in() && $context) {
	$username = elgg_get_logged_in_user_entity()->username;
	$filter_context = elgg_extract('filter_context', $vars, 'mine');

if ($filter_context == 'all') {$filter_context = 'all';} 

	// generate a list of default tabs
	$tabs = array(
		'all' => array( //commented out for private_river
			'text' => elgg_echo('all'),
			'href' => (isset($vars['all_link'])) ? $vars['all_link'] : "$context/all",
			'selected' => ($filter_context == 'all'),
			'priority' => 200,
		),
		'mine' => array(
			'text' => elgg_echo('mine'),
			'href' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "$context/", //changed from context/owner/$username
			'selected' => ($filter_context == 'mine'),
			'priority' => 300,
		),
		'friend' => array(
			'text' => elgg_echo('friends'),
			'href' => (isset($vars['friend_link'])) ? $vars['friend_link'] : "$context/friends/$username",
			'selected' => ($filter_context == 'friends'),
			'priority' => 400,
		),
	);

	foreach ($tabs as $name => $tab) {
		$tab['name'] = $name;
		
		elgg_register_menu_item('filter', $tab);
	}

	echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
}
} else { // lets remone the tab for Private_river
	if (isset($vars['filter_override'])) {
	echo $vars['filter_override'];
	return true;
}

$context = elgg_extract('context', $vars, elgg_get_context());
if (elgg_is_logged_in() && $context) {
	$username = elgg_get_logged_in_user_entity()->username;
	$filter_context = elgg_extract('filter_context', $vars, 'mine');

if ($filter_context == 'all') {$filter_context = 'mine';} //added for private_river so tabs default to mine

	// generate a list of default tabs
	$tabs = array(
		'mine' => array(
			'text' => elgg_echo('mine'),
			'href' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "$context/", //changed from context/owner/$username
			'selected' => ($filter_context == 'mine'),
			'priority' => 300,
		),
		'friend' => array(
			'text' => elgg_echo('friends'),
			'href' => (isset($vars['friend_link'])) ? $vars['friend_link'] : "$context/friends/$username",
			'selected' => ($filter_context == 'friends'),
			'priority' => 400,
		),
	);

	foreach ($tabs as $name => $tab) {
		$tab['name'] = $name;
		
		elgg_register_menu_item('filter', $tab);
	}

	echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
}
}
//print_r ($vars); //dbg

