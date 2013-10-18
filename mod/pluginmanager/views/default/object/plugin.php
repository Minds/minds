<?php
/**
 * Used to show plugin user settings.
 *
 * @package Elgg.Core
 * @subpackage Plugins
 *
 */

$plugin = $vars['entity'];

if (elggmulti_is_plugin_available($plugin->getID())) {
    if (!$plugin->isValid()) {
	    echo elgg_view('plugin/plugin/invalid', $vars);
    } else {
	    echo elgg_view('plugin/plugin/full', $vars);
    }
}
else
{
    echo elgg_view('object/plugin/multi_disabled', $vars);
}