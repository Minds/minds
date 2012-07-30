<?php
/**
 * Tidypics settings
 */

if (tidypics_is_upgrade_available()) {
	echo '<div class="elgg-admin-notices">';
	echo '<p>';
	echo elgg_view('output/url', array(
		'text' => elgg_echo('tidypics:upgrade'),
		'href' => 'action/photos/admin/upgrade',
		'is_action' => true,
	));
	echo '</p>';
	echo '</div>';
}

echo elgg_view_form('photos/admin/settings');
