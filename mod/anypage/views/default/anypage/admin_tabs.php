<?php
/**
 * Tabs for admin area
 *
 * @uses ElggEntity $vars['current_page'] The current page. Optional.
 */

$current_page = elgg_extract('current_page', $vars);

$pages = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'anypage',
	'limit' => 0
));

$tabs = array();
foreach ($pages as $page) {
	$tabs[] = array(
		'text' => $page->title,
		'href' => '/admin/appearance/anypage?guid=' . $page->getGUID(),
		'selected' => ($page == $current_page)
	);
}

if ($tabs) {
	echo elgg_view('navigation/tabs', array(
		'tabs' => $tabs
	));
}