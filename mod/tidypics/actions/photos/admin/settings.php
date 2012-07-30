<?php
/**
 * Save Tidypics plugin settings
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$plugin = elgg_get_plugin_from_id('tidypics');

$params = get_input('params');
foreach ($params as $k => $v) {
	if (!$plugin->setSetting($k, $v)) {
		register_error(elgg_echo('plugins:settings:save:fail', array('tidypics')));
		forward(REFERER);
	}
}

// image sizes
$image_sizes = array();
$image_sizes['large_image_width'] = get_input('large_image_width');
$image_sizes['large_image_height'] = get_input('large_image_height');
$image_sizes['small_image_width'] = get_input('small_image_width');
$image_sizes['small_image_height'] = get_input('small_image_height');
$image_sizes['tiny_image_width'] = get_input('tiny_image_width');
$image_sizes['tiny_image_height'] = get_input('tiny_image_height');
$plugin->setSetting('image_sizes', serialize($image_sizes));


system_message(elgg_echo('tidypics:settings:save:ok'));
forward(REFERER);
