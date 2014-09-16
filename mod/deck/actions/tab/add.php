<?php

$name = strtolower(get_input('tab_name'));

// Get the settings of the current user
$owner = elgg_get_logged_in_user_entity();

$tabs = deck_river_get_tabs($owner->guid);

$max_nbr_tabs = elgg_get_plugin_setting('max_nbr_tabs', 'elgg-deck_river');
if ($max_nbr_tabs && count($tabs) > $max_nbr_tabs) {
	register_error(elgg_echo('deck_river:limitTabReached'));
	forward(REFERER);
}

// Check that the tab name is not already in use
foreach($tabs as $tab){
	if($tab->name == $name){
		register_error(elgg_echo('deck_river:add:tab:error'));
		forward(REFERER);
	}
}

$tab = new ElggDeckTab();
$tab->name = $name;
if($tab->save()){
	forward(elgg_get_site_url() . 'activity/' . $name);
} else {
	register_error(elgg_echo('deck_river:add:tab:error'));
	forward(REFERER);
}

	/*if (function_exists('ggouv_execute_js')) {
		$site = elgg_get_site_url();
		$script = <<<TEXT
$('body').click();
$('.elgg-layout .elgg-menu-deck-river .elgg-menu-item-plus').before(
'<li class="elgg-menu-item-$tab"><a href="{$site}activity/$tab" class="column-deletable">$tab</a><a class="delete-tab" href="#"><span class="elgg-icon elgg-icon-delete "></span></a></li>');
TEXT;
		ggouv_execute_js($script);
	}*/



