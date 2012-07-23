<?php
/**
 * Market Categories pages menu
 *
 * @uses $vars['type']
 */

$type = $vars['category'];

if (empty($type)) {
	 $type = 'all';
}

//set the url
$url = $vars['url'] . "market/category/";

$cats = elgg_get_plugin_setting('market_categories', 'market');
$categories = string_to_tag_array(elgg_get_plugin_setting('market_categories', 'market'));
array_unshift($categories, "all");
$tabs = array();
foreach ($categories as $category) {
	$tabs[] = array(
		'title' => elgg_echo("market:{$category}"),
		'url' => $url . $category,
		'selected' => $category == $type,
	);
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));



