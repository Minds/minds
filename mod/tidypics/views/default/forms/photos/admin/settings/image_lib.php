<?php
/**
 * Image library settings
 */

$plugin = $vars['plugin'];

echo'<div>';
echo elgg_echo('tidypics:settings:image_lib') . ': ';
echo elgg_view('input/dropdown', array(
	'name' => 'params[image_lib]',
	'options_values' => tidypics_get_image_libraries(),
	'value' => $plugin->image_lib,
));
echo '</div>';
echo '<div>';
echo elgg_echo('tidypics:settings:im_path') . ' ';
echo elgg_view("input/text", array('name' => 'params[im_path]', 'value' => $plugin->im_path));
echo '</div>';

