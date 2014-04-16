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
	$filter_context = elgg_extract('filter_context', $vars);
	$tabs = array(
		'featured' => array(
			'text' => elgg_echo('river:featured'),
			'href' => $context."/featured",
			'selected' => ($filter_context == 'featured'),
			'priority' => 200,
		),
/*		'trending' => array(
                        'text' => elgg_echo('trending'),
                        'href' => $context."/trending",
                        'selected' => ($filter_context == 'trending'),
                        'priority' => 300,
                ),*/
		'friend' => array(
			'text' => elgg_echo('friends'),
			'href' => $context."/channels/$username",
			'selected' => ($filter_context == 'friends' || !$filter_context),
			'priority' => 400,
		),
		/*'thumbsup' => array(
			'text' => '&#128077;',
			'href' => $context."/thumbsup",
			'selected' => ($filter_context == 'thumbsup'),
			'class' => 'entypo',
			'priority' => 500,
		),
		'thumbsdown' => array(
			'text' => '&#128078;',
			'href' => $context."/thumbsdown",
			'selected' => ($filter_context == 'thumbsdown'),
			'class'=>'entypo',
			'priority' => 600,
		),*/

		'deck' => array(
                        'text' => elgg_echo('My Social Network Feeds'),
                        'href' => '/activity',
                        'selected' => false,
                        'priority' => 500,
                ),
        'toggle' => array(
 			'text' => get_input('linear') == 'on' ? "&#59404;" :  "&#59405;",
 			'link_class'=>'entypo tooltip w',
			'href' => get_input('linear') == 'on' ? '?linear=off' : '?linear=on',
			'selected' => false,
			'title'=>'Toggle the layout',
    		'priority' => 1,
    		'section'=>'right'
                ),
               
	);
	if(elgg_is_admin_logged_in()){
	$tabs['all'] = array(
			'text' => elgg_echo('all'),
			'href' => (isset($vars['all_link'])) ? $vars['all_link'] : "$context/all",
			'selected' => ($filter_context == 'all'),
			'priority' => 100,
		);
	}
	
	foreach ($tabs as $name => $tab) {
		$tab['name'] = $name;
		
		elgg_register_menu_item('filter', $tab);
	}

	echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
}
