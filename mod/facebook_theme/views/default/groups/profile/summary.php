<?php
/**
 * Group profile summary
 *
 * Icon and profile fields
 *
 * @uses $vars['group']
 */

if (!isset($vars['entity']) || !$vars['entity']) {
	echo elgg_echo('groups:notfound');
	return true;
}

echo elgg_view_module('info', 'Info', elgg_view('groups/profile/fields', $vars));

echo elgg_view_module('info', 'Stats', elgg_view('groups/profile/stats', $vars));
