<?php
/**
 * Used to show plugin user settings.
 *
 * @package Elgg.Core
 * @subpackage Plugins
 *
 */

$plugin = $vars['entity'];

    if (!$plugin->isValid()) {
	    echo elgg_view('plugin/plugin/invalid', $vars);
    } else {
	    echo elgg_view('plugin/plugin/full', $vars);
    }

