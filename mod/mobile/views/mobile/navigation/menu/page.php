<?php
/**
 * Page menu
 *
 * @uses $vars['menu']
 * @uses $vars['selected_item']
 * @uses $vars['class']
 * @uses $vars['name']
 * @uses $vars['show_section_headers']
 */

$headers = elgg_extract('show_section_headers', $vars, false);

$class = 'nav nav-list';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

if (isset($vars['selected_item'])) {
	$parent = $vars['selected_item']->getParent();

	while ($parent) {
		$parent->setSelected();
		$parent = $parent->getParent();
	}
}

foreach ($vars['menu'] as $section => $menu_items) {
	echo elgg_view('navigation/menu/elements/section', array(
		'items' => $menu_items,
		'class' => "$class elgg-menu-page-$section",
		'section' => $section,
		'name' => $vars['name'],
		'show_section_headers' => $headers
	));
}
