<?php

$tab = sanitise_string(strtolower(get_input('tab_name')));
$tab_old = sanitise_string(get_input('old_tab_name'));

// Get the settings of the current user
$owner = elgg_get_logged_in_user_entity();
$user_river_options = json_decode($owner->getPrivateSetting('deck_river_settings'), true);

if ($tab && isset($user_river_options[$tab_old]) && $tab_old != 'default') {

	foreach ($user_river_options as $key => $value) {
		if ($key == $tab_old) {
			$user_river_options_new[$tab] = $value;
		} else {
			$user_river_options_new[$key] = $value;
		}
	}

	$owner->setPrivateSetting('deck_river_settings', json_encode($user_river_options_new));
	echo json_encode(array(
		'old_tab_name' => $tab_old,
		'tab_name' => $tab,
		'user_river_settings' => $user_river_options_new
	));
} else {
	register_error('deck_river:rename:tab:error');
}
