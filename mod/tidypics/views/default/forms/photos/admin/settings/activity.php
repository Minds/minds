<?php
/**
 * River integration
 */

$plugin = $vars['plugin'];

echo '<div>';
echo elgg_echo('tidypics:settings:img_river_view') . ': ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[img_river_view]',
	'options_values' => array(
		'all' => elgg_echo('tidypics:option:all'),
		'batch' => '1',
		'none' => elgg_echo('tidypics:option:none'),
	),
	'value' => $plugin->img_river_view,
));
echo '</div>';
echo '<div>';
echo elgg_echo('tidypics:settings:album_river_view') . ': ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[album_river_view]',
	'options_values' => array(
		'cover' => elgg_echo('tidypics:option:cover'),
		'set' => elgg_echo('tidypics:option:set'),
	),
	'value' => $plugin->album_river_view,
));
echo '</div>';

