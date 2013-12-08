<?php
/**
 * Action for pin a network
 */

action_gatekeeper();

// don't filter since we strip and filter escapes some characters
$networks = get_input('networks');

if (!$networks) {
	register_error(elgg_echo('deck_river:error:pin'));
} else {

	$user_guid = elgg_get_logged_in_user_guid();

	if (count($networks['pinned']) < 5) {
		set_private_setting($user_guid, 'user_deck_river_accounts_in_wire', json_encode($networks));
		echo true;
	} else {
		$user_deck_river_accounts_in_wire = json_decode(get_private_setting($user_guid, 'user_deck_river_accounts_in_wire'), true);
		$user_deck_river_accounts_in_wire['position'] = $networks['position'];
		set_private_setting($user_guid, 'user_deck_river_accounts_in_wire', json_encode($user_deck_river_accounts_in_wire));
		register_error(elgg_echo('deck_river:error:pin:too_much'));
	}

}