<?php
/**
 * Thumbnail sizes
 */

$plugin = $vars['plugin'];

echo '<span class="elgg-text-help">' . elgg_echo('tidypics:settings:sizes:instructs') . '</span>';
$image_sizes = unserialize($plugin->image_sizes);
echo '<table>';
$sizes = array('large', 'small', 'tiny');
foreach ($sizes as $size) {
	echo '<tr>';
	echo '<td class="pas">';
	echo elgg_echo("tidypics:settings:{$size}size");
	echo '</td><td class="pas">';
	echo 'width: ';
	echo elgg_view('input/text', array(
		'name' => "{$size}_image_width",
		'value' => $image_sizes["{$size}_image_width"],
		'class' => 'tidypics-input-thin',
	));
	echo '</td><td class="pas">';
	echo 'height: ';
	echo elgg_view('input/text', array(
		'name' => "{$size}_image_height",
		'value' => $image_sizes["{$size}_image_height"],
		'class' => 'tidypics-input-thin',
	));
	echo '</td>';
	echo '</tr>';
}
echo '</table>';
