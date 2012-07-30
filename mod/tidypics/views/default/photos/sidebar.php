<?php
/**
 * Sidebar view
 */

$page = elgg_extract('page', $vars);
$image = elgg_extract('image', $vars);
if ($image && $page == 'view') {
	if (elgg_get_plugin_setting('exif', 'tidypics')) {
		echo elgg_view('photos/sidebar/exif', $vars);
	}
}

if ($page == 'upload') {
	if (elgg_get_plugin_setting('quota', 'tidypics')) {
		echo elgg_view('photos/sidebar/quota', $vars);
	}
}