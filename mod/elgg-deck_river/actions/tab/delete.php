<?php

$tab = sanitise_string(get_input('tab'));

// Get settings of the current user
$owner = elgg_get_logged_in_user_entity();
$user_river_options = json_decode($owner->getPrivateSetting('deck_river_settings'), true);

if ($tab && $user_river_options && $tab != 'default') {
	if (isset($user_river_options[$tab])) {
		unset($user_river_options[$tab]);

		$json_user_river_options = json_encode($user_river_options);
		$owner->setPrivateSetting('deck_river_settings', $json_user_river_options);
		echo $json_user_river_options;
	} else {
		register_error('deck_river:delete:tab:error');
	}
} else {
	register_error('deck_river:delete:tab:error');
}
forward(REFERER);
