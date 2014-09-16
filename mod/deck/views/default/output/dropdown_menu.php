<?php
/**
 * Dropdown menu
 * Display a dropdown menu with submenus
 *
 * @package Elgg-deck_river
 *
 * @uses $vars['class'] Extend class. Use 'invert' to display menu above the button.
 * @uses $vars['menu'] Array of links to compose the menu
 *
 */

$class = 'elgg-button elgg-button-dropdown elgg-submenu';
$additional_class = elgg_extract('class', $vars, '');
if ($additional_class) {
	$vars['class'] = "$class $additional_class";
} else {
	$vars['class'] = $class;
}


$menu = '';
foreach ($vars['menu'] as $key => $value) {
	$menu .= '<li>' . $value . '</li>';
}

unset($vars['menu']);

$attributes = elgg_format_attributes($vars);

echo '<div ' . $attributes . '>';

echo '<ul class="elgg-menu elgg-module-popup hidden">';

echo $menu;

echo '</ul>';

echo '</div>';