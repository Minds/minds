<?php
/**
 * Used to show plugin user settings.
 *
 * @package Elgg.Core
 * @subpackage Plugins
 *
 */

if (!elgg_in_context('admin')) {
	forward('/', 403);
}

$plugin = $vars['entity'];

if (!$plugin->isValid()) {
	echo elgg_view('plugin/plugin/invalid', $vars);
} else {
	echo elgg_view('plugin/plugin/full', $vars);
}
