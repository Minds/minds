<?php
/**
 * Action add facebook page
 */

action_gatekeeper();
elgg_load_library('deck_river:authorize');

if (deck_river_count_networks_account('all') >= elgg_get_plugin_setting('max_accounts', 'elgg-deck_river')) {
	register_error(elgg_echo('deck_river:network:too_many_accounts', array(elgg_get_site_entity()->name)));
} else {

	$account_guid = (int) get_input('facebook_account');
	$page_data = (array) get_input('page_data');

	$account = get_entity($account_guid);
	$user_guid = elgg_get_logged_in_user_guid();

	if (deck_river_get_networks_account('facebook_account', $user_guid, $page_data['id'])) {
		register_error(elgg_echo('deck_river:facebook:error:page:already'));
	} else {

		if ($account && $account->getSubtype() == 'facebook_account' && $account->getOwnerGUID() == $user_guid) {

			if (is_array($page_data)) {

				$fb_page_account = new ElggObject;
				$fb_page_account->subtype = 'facebook_account';
				$fb_page_account->access_id = 0;
				$fb_page_account->user_id = $page_data['id']; // we store in user_id but in facebook api this is id
				$fb_page_account->parent_id = $account->user_id; // and parent id are stored here
				$fb_page_account->name = $page_data['name'];
				$fb_page_account->username = $account->name;
				$fb_page_account->oauth_token = $page_data['access_token'];

				if ($fb_page_account->save()) {
					// trigger authorization hook
					elgg_trigger_plugin_hook('authorize', 'elgg-deck_river', array('token' => $token));

					$fb_page_account->time_created = time(); // Don't now why time_created is not filled
					$fb_guid = $fb_page_account->getGUID();

					echo json_encode(array(
						'network' => 'facebook',
						'network_box' => elgg_view_entity($fb_page_account, array(
												'view_type' => 'in_network_box',
											)),
						'full' => '<li id="elgg-object-' . $fb_guid . '" class="elgg-item">' . elgg_view_entity($fb_page_account) . '</li>'
					));

				}

			} else {
				register_error(elgg_echo('deck_river:facebook:error'));
			}


		} else {
			register_error(elgg_echo('deck_river:facebook:error'));
		}

	}

}