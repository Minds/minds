<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

// Get input
$selected_category = get_input('cat');

if ($selected_category == 'all') {
	$category = '';
} elseif ($selected_category == '') {
	$category = '';
	$selected_category = 'all';
} else {
	$category = $selected_category;
}
elgg_set_context('market');
elgg_pop_breadcrumb();
elgg_register_title_button();

$tabs = elgg_view('market/menu', array('category' => $selected_category));

//set market title
$title = sprintf(elgg_echo('market:category:title'), elgg_echo("market:{$selected_category}"));

$options = array(
	'types' => 'object',
	'subtypes' => 'market',
	'limit' => 5,
	'full_view' => false,
	'pagination' => true,
	'view_type_toggle' => false,
);

// Get a list of market posts in a specific category
if (!empty($category)) {
	elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
	elgg_push_breadcrumb(elgg_echo("market:{$category}"));
	$options['metadata_name'] = "marketcategory";
	$options['metadata_value'] = $selected_category;
	$content = elgg_list_entities_from_metadata($options);
} else {
	elgg_push_breadcrumb(elgg_echo('market:title'));
	$content = elgg_list_entities($options);
}

if (!$content) {
	$content = elgg_echo('market:none:found');
}

// Show market sidebar
$sidebar = elgg_view("market/sidebar");

$params = array(
		'filter' => $tabs,
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);

