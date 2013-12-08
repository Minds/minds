<?php

$tab = strtolower(get_input('tab_name'));

// Get the settings of the current user
$owner = elgg_get_logged_in_user_entity();
$user_river_options = json_decode($owner->getPrivateSetting('deck_river_settings'), true);

if (!$user_river_options[$tab]) {

	$max_nbr_tabs = elgg_get_plugin_setting('max_nbr_tabs', 'elgg-deck_river');

	if ($max_nbr_tabs && count($user_river_options) > $max_nbr_tabs) {
		register_error(elgg_echo('deck_river:limitTabReached'));
		forward(REFERER);
	}

	$user_river_options[$tab] = array();
	$json_user_river_options = json_encode($user_river_options);
	
	$owner->setPrivateSetting('deck_river_settings', $json_user_river_options);

	echo $json_user_river_options;

	if (function_exists('ggouv_execute_js')) {
		$site = elgg_get_site_url();
		$script = <<<TEXT
$('body').click();
$('.elgg-layout .elgg-menu-deck-river .elgg-menu-item-plus').before(
'<li class="elgg-menu-item-$tab"><a href="{$site}activity/$tab" class="column-deletable">$tab</a><a class="delete-tab" href="#"><span class="elgg-icon elgg-icon-delete "></span></a></li>');
TEXT;
		ggouv_execute_js($script);
	}

	forward(elgg_get_site_url() . 'activity/' . $tab);
} else {
	register_error(elgg_echo('deck_river:add:tab:error'));
	forward(REFERER);
}
