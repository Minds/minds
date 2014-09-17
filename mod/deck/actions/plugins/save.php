<?php
/**
 * Elgg-deck_river
 * Settings need to be modified to set default columns...
 */

$params = get_input('params');
$reset_user = get_input('reset_user');
$plugin_id = get_input('plugin_id');
$plugin = elgg_get_plugin_from_id($plugin_id);

if (!($plugin instanceof ElggPlugin)) {
	register_error(elgg_echo('plugins:settings:save:fail', array($plugin_id)));
	forward(REFERER);
}

$plugin_name = $plugin->getManifest()->getName();

$result = false;

foreach ($params as $k => $v) {
	$result = $plugin->setSetting($k, $v);
	if (!$result) {
		register_error(elgg_echo('plugins:settings:save:fail', array($plugin_name)));
		forward(REFERER);
		exit;
	}
}

system_message(elgg_echo('plugins:settings:save:ok', array($plugin_name)));

if ($reset_user) {
	if ( set_private_setting($reset_user, 'deck_river_settings', null) ) {
		system_message(elgg_echo('deck_river:settings:reset_user:ok', array($reset_user)));
	} else {
		register_error(elgg_echo('deck_river:settings:reset_user:nok', array($reset_user)));
	}
}

forward(REFERER);