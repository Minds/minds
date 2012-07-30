<?php
/**
 * EXIF sidebar module
 */

$image = $vars['image'];

elgg_load_library('tidypics:exif');

$exif = tp_exif_formatted($image);
if ($exif) {
	$title = "EXIF";
	$body = '<table class="elgg-table elgg-table-alt">';
	foreach ($exif as $key => $value) {
		$body .= "<tr><td>$key</td><td>$value</td></tr>";
	}
	$body .= '</table>';

	echo elgg_view_module('aside', $title, $body);
}
