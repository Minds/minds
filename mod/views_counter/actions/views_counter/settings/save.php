<?php
/**
 * Saves global plugin settings.
 *
 * This action can be overriden for a specific plugin by creating the
 * settings/<plugin_id>/save action in that plugin.
 *
 * @uses array $_REQUEST['params']    A set of key/value pairs to save to the ElggPlugin entity
 * @uses int   $_REQUEST['plugin_id'] The ID of the plugin
 *
 * @package Elgg.Core
 * @subpackage Plugins.Settings
 */

$params = get_input('params');
$plugin_id = get_input('plugin_id');
$plugin = elgg_get_plugin_from_id($plugin_id);

if (!($plugin instanceof ElggPlugin)) {
	register_error(elgg_echo('plugins:settings:save:fail', array($plugin_id)));
	forward(REFERER);
}

$plugin_name = $plugin->getManifest()->getName();

$result = false;

// need to process array - elgg doesn't do this normally
// Get the removed types saved in database
$removed_types = unserialize(elgg_get_plugin_setting('remove_views_counter','views_counter'));
$removed_types = ($removed_types) ? ($removed_types) : array();

// Get the previous added types
$previous_types = unserialize($plugin->add_views_counter);

// Checking which types were removed for the admin right now and include them in the remove_views_counter array
foreach($previous_types as $previous_type) {
  // If the type was removed right now and It was not already added as a removed type then let's add It now
  if ((!in_array($previous_type,$params['value'])) && (!in_array($previous_type,$removed_types))) {
	$removed_types[] = $previous_type;
  }
}

// Save It on the plugin settings
$plugin->setSetting('remove_views_counter', serialize($removed_types));
					
// Save the add_views_counter settings as a serialized value
$params['add_views_counter'] = serialize($params['add_views_counter']);				

foreach ($params as $k => $v) {
	$result = $plugin->setSetting($k, $v);
	if (!$result) {
		register_error(elgg_echo('plugins:settings:save:fail', array($plugin_name)));
		forward(REFERER);
		exit;
	}
}

system_message(elgg_echo('plugins:settings:save:ok', array($plugin_name)));
forward(REFERER);