<?php
/**
 * Elgg title element
 *
 * @uses $vars['title'] The page title
 * @uses $vars['class'] Optional class for heading
 */

if (isset($vars['header'])) {
	echo $vars['header'];
	return true;
}

$class = '';
if (isset($vars['class'])) {
	$class = " class=\"{$vars['class']}\"";
}

echo elgg_view_menu('title', array('sort_by' => 'priority'));
echo "<h1{$class}>{$vars['title']}</h1>";
echo elgg_view('navigation/breadcrumbs');