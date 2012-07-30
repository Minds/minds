<?php
/**
 * Admin page
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$tab = get_input('tab', 'settings');

echo elgg_view('navigation/tabs', array(
	'tabs' => array(
		array(
			'text' => elgg_echo('settings'),
			'href' => '/admin/settings/photos',
			'selected' => ($tab == 'settings'),
		),
		array(
			'text' => elgg_echo('tidypics:server_info'),
			'href' => '/admin/settings/photos?tab=server_info',
			'selected' => ($tab == 'server_info'),
		),
		array(
			'text' => elgg_echo('tidypics:server_config'),
			'href' => '/admin/settings/photos?tab=server_config',
			'selected' => ($tab == 'server_config'),
		),
		array(
			'text' => 'ImageMagick',
			'href' => '/admin/settings/photos?tab=image_lib',
			'selected' => ($tab == 'image_lib'),
		),
		array(
			'text' => elgg_echo('tidypics:settings:thumbnail'),
			'href' => '/admin/settings/photos?tab=thumbnail',
			'selected' => ($tab == 'thumbnail'),
		),
		array(
			'text' => elgg_echo('tidypics:settings:help'),
			'href' => '/admin/settings/photos?tab=help',
			'selected' => ($tab == 'help'),
		),
	)
));

switch ($tab) {
	case 'server_info':
		echo elgg_view('admin/settings/photos/server_info');
		break;

	case 'server_config':
		echo elgg_view('admin/settings/photos/server_config');
		break;

	case 'image_lib':
		echo elgg_view('admin/settings/photos/image_lib');
		break;

	case 'thumbnail':
		echo elgg_view('admin/settings/photos/thumbnail');
		break;

	case 'help':
		echo elgg_view('admin/settings/photos/help');
		break;

	default:
	case 'settings':
		echo elgg_view('admin/settings/photos/settings');
		break;
}
