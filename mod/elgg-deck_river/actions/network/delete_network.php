<?php
/**
 * Action to delete a network
 */

action_gatekeeper();

$network_guid = (int) get_input('guid');

$user = elgg_get_logged_in_user_entity();

if (!$network_guid) {
	register_error(elgg_echo('deck_river:network:revoke:error'));
} else {

	$network = get_entity($network_guid);

	if ($network->getOwnerGUID() == $user->getGUID()) {
		elgg_load_library('deck_river:authorize');
		if ($network->getSubtype() == 'twitter_account') {
			deck_river_twitter_api_revoke($user->getGUID(), $network->user_id);
		}
		if ($network->getSubtype() == 'facebook_account') {
			deck_river_facebook_revoke($user->getGUID(), $network->user_id);
		}
	} else {
		register_error(elgg_echo('deck_river:network:revoke:error'));
	}
}

forward('/authorize/applications/' . $user->username);