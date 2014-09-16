<?php
/**
 * Group of output form (button, input, dropdown menu...)
 * Displays a grouped output view
 *
 * @package Elgg-deck_river
 *
 * @uses $vars['group'] An array of group views to display
 * @uses $vars['class'] Class of the group
 *
 */

$class = 'output-group';
$additional_class = elgg_extract('class', $vars, '');
if ($additional_class) {
	$vars['class'] = "$class $additional_class";
} else {
	$vars['class'] = $class;
}

$group = '';
foreach ($vars['group'] as $key => $value) {
	$group .= $value;
}

unset($vars['group']);

$attributes = elgg_format_attributes($vars);

echo "<div $attributes>";

echo $group;

echo '</div>';