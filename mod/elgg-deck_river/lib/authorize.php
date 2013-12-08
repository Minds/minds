<?php
/**
 * Common library of functions used by Twitter Services.
 *
 * @package elgg-deck_river
 */



/**
 * Get networks account for the currently logged in user.
 */
function deck_river_get_networks_account($network, $user_guid = null, $user_id = null, $shared = false) {
	if (!$network) return false;
	if (!$user_guid) $user_guid = elgg_get_logged_in_user_guid();

	if ($network == 'all') $network = array('twitter_account', 'facebook_account');

	$params = array(
		'type' => 'object',
		'subtype' => $network,
		'owner_guid' => $user_guid,
		'limit' => 0
	);

	if ($user_id) {
		$params = array_merge($params, array(
			'metadata_name' => 'user_id',
			'metadata_value' => $user_id,
		));
	}

	if (!$shared) {
		return elgg_get_entities_from_metadata($params);
	} else {
		if ($accounts = elgg_get_entities_from_metadata($params)) {
			return array_merge($accounts, deck_river_get_shared_accounts($network, $user_guid));
		} else {
			return deck_river_get_shared_accounts($network, $user_guid);
		}
	}
}



/**
 * count networks account for the currently logged in user.
 */
function deck_river_count_networks_account($network, $user_guid = null, $user_id = null) {
	if (!$network) return false;
	if (!$user_guid) $user_guid = elgg_get_logged_in_user_guid();

	if ($network == 'all') $network = array('twitter_account', 'facebook_account');

	$params = array(
		'type' => 'object',
		'subtype' => $network,
		'owner_guid' => $user_guid,
		'limit' => 0,
		'count' => true
	);

	if ($user_id) {
		$params = array_merge($params, array(
			'metadata_name' => 'user_id',
			'metadata_value' => $user_id,
		));
	}

	return elgg_get_entities_from_metadata($params);
}


/**
 * Return all accounts where user is shared with.
 * @param  [type] $user_guid the user
 * @return [type]          array of guid of accounts
 */
function deck_river_get_shared_accounts($network = 'all', $user_guid = null) {
	global $CONFIG;

	if (!$user_guid) $user_guid = elgg_get_logged_in_user_guid();

	if ($network == 'all') $network = array('twitter_account', 'facebook_account');

	$site_id = $CONFIG->site_guid;
	$hash = $user_guid . $site_id . 'get_shared_accounts';
	$account_array = array();

	if ($SHARED_ACCOUNTS_CACHE[$hash]) {
		$access_array = $cache[$hash];
	} else {

		// Get ACL memberships
		$query = "SELECT am.access_collection_id"
			. " FROM {$CONFIG->dbprefix}access_collection_membership am"
			. " LEFT JOIN {$CONFIG->dbprefix}access_collections ag ON ag.id = am.access_collection_id"
			. " WHERE am.user_guid = $user_guid AND (ag.site_guid = $site_id OR ag.site_guid = 0) AND ag.name = 'shared_network_acl'";

		$collections = get_data($query);
		if ($collections) {
			foreach ($collections as $collection) {
				if (!empty($collection->access_collection_id)) {
					$access_array[] = (int)$collection->access_collection_id;
				}
			}

			$a = elgg_set_ignore_access(true);
			$account_array = elgg_get_entities(array(
				'type' => 'object',
				'subtype' => $network,
				'limit' => 0,
				'wheres' => array("(e.access_id IN (" . implode(",", $access_array) . "))")
			));
			elgg_set_ignore_access($a);
		}

		$SHARED_ACCOUNTS_CACHE[$hash] = $account_array;
	}

	return $account_array;
}



/**
 * User-initiated Twitter authorization
 *
 * Callback action from Twitter registration. Registers a single Elgg user with
 * the authorization tokens. Will revoke access from previous users when a
 * conflict exists.
 *
 */
function deck_river_twitter_authorize() {
	$oauth_token = get_input('oauth_token', false);
	$error = false;

	if (!$oauth_token) {
		$error[] = elgg_echo('deck_river:network:authorize:error');
	}

	// check if user has too many accounts
	if (deck_river_count_networks_account('all') >= elgg_get_plugin_setting('max_accounts', 'elgg-deck_river')) {
		$error[] = elgg_echo('deck_river:network:too_many_accounts', array(elgg_get_site_entity()->name));
	}

	// get token
	elgg_load_library('deck_river:twitter_async');
	$twitter_consumer_key = elgg_get_plugin_setting('twitter_consumer_key', 'elgg-deck_river');
	$twitter_consumer_secret = elgg_get_plugin_setting('twitter_consumer_secret', 'elgg-deck_river');
	$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);
	$twitterObj->setToken($oauth_token);
	$token = $twitterObj->getAccessToken();

	// make sure don't register twice this twitter account for this user.
	if (deck_river_get_networks_account('twitter_account', elgg_get_logged_in_user_guid(), $token->user_id)) {
		$error[] = elgg_echo('deck_river:network:authorize:already_done');
	}

	if (!$error && $token) {

		// get avatar
		$twitterObj = new EpiTwitter($twitter_consumer_key, $twitter_consumer_secret, $token->oauth_token, $token->oauth_token_secret);
		$userInfo = $twitterObj->get('/account/verify_credentials.json');

		$twitter_account = new ElggObject;
		$twitter_account->subtype = 'twitter_account';
		$twitter_account->access_id = 0;
		$twitter_account->user_id = $token->user_id;
		$twitter_account->screen_name = $token->screen_name;
		$twitter_account->oauth_token = $token->oauth_token;
		$twitter_account->oauth_token_secret = $token->oauth_token_secret;
		$twitter_account->avatar = $userInfo->response['profile_image_url_https'];

		if ($twitter_account->save()) {
			// trigger authorization hook
			elgg_trigger_event('authorize', 'deck_river:twitter', $twitter_account);

			$twitter_account->time_created = time(); // Don't now why time_created is not filled

			$account_output = array(
				'network' => 'twitter',
				'network_box' => elgg_view_entity($twitter_account, array(
										'view_type' => 'in_network_box',
									)),
				'full' => '<li id="elgg-object-' . $twitter_account->getGUID() . '" class="elgg-item">' . elgg_view_entity($twitter_account) . '</li>'
			);

			// add head and foot for js script
			echo elgg_view('page/elements/head');
			echo elgg_view('page/elements/foot');

			echo '<script type="text/javascript">$(document).ready(function() {elgg.deck_river.network_authorize(' . json_encode($account_output) . ');});</script>';
		} else {
			$error[] = elgg_echo('deck_river:network:authorize:error');
		}
	}

	if ($error) {
		// add head and foot for js script
		echo elgg_view('page/elements/head');
		echo elgg_view('page/elements/foot');

		echo elgg_echo('deck_river:network:authorize:error');
		echo '<script type="text/javascript">$(document).ready(function() {authorizeError = '. json_encode($error) .';elgg.deck_river.network_authorize(false);});</script>';
	}

}

/**
 * Remove twitter access for the currently logged in user.
 */
function deck_river_twitter_api_revoke($user_guid = null, $user_id = null, $echo = true) {
	if (!$user_guid) $user_guid = elgg_get_logged_in_user_guid();

	if ($user_guid && elgg_instanceof(get_entity($user_guid), 'user')) {

		$user_deck_river_accounts_in_wire = json_decode(get_private_setting($user_guid, 'user_deck_river_accounts_in_wire'), true);

		$entities = deck_river_get_networks_account('twitter_account', $user_guid, $user_id);
		foreach ($entities as $entity) {
			if ($entity->canEdit()) {
				// remove account from pinned accounts
				$user_deck_river_accounts_in_wire['position'] = array_diff($user_deck_river_accounts_in_wire['position'], array($entity->getGUID()));
				$user_deck_river_accounts_in_wire['pinned'] = array_diff($user_deck_river_accounts_in_wire['pinned'], array($entity->getGUID()));
				set_private_setting($user_guid, 'user_deck_river_accounts_in_wire', json_encode($user_deck_river_accounts_in_wire));

				if (get_readable_access_level($entity->access_id) == 'shared_network_acl') {
					delete_access_collection($entity->access_id);
				}

				// remove account
				$entity->delete();
			}
		}

		if ($echo && $entities) system_message(elgg_echo('deck_river:twitter:revoke:success'));
		return true;
	} else {
		register_error(elgg_echo('deck_river:network:revoke:error'));
		return false;
	}
}



function deck_river_get_facebook_scope() {
	return 'read_friendlists,
			read_insights,
			read_mailbox,
			read_requests,
			read_stream,
			share_item,
			export_stream,
			status_update,
			video_upload,
			photo_upload,
			create_note,
			create_event,
			manage_friendlists,
			manage_notifications,
			manage_pages,
			publish_actions,
			publish_stream,
			user_about_me,
			user_activities,
			user_events,
			user_friends,
			user_groups,
			user_likes,
			user_location,
			user_relationships,
			user_subscriptions,
			user_website,
			friends_notes,
			friends_status,
			friends_groups,
			friends_likes,
			friends_photos,
			friends_relationships,
			friends_activities,
			friends_events,
			friends_videos';
}



function deck_river_facebook_authorize() {
	$code = get_input('code', false);
	$error = false;

	if (!$code) {
		$error[] = elgg_echo('deck_river:network:authorize:error');
	}

	// check if user has too many accounts
	if (deck_river_count_networks_account('all') >= elgg_get_plugin_setting('max_accounts', 'elgg-deck_river')) {
		$error[] = elgg_echo('deck_river:network:too_many_accounts', array(elgg_get_site_entity()->name));
	}

	elgg_load_library('deck_river:facebook_sdk');
	$facebook = new Facebook(array(
		'appId'  => elgg_get_plugin_setting('facebook_app_id', 'elgg-deck_river'),
		'secret' => elgg_get_plugin_setting('facebook_app_secret', 'elgg-deck_river'),
		'cookie' => true
	));
	$token = $facebook->getAccessToken();

	$facebook->setAccessToken($token);
	$fbUserProfile = $facebook->api('/me'); // Récupere l'utilisateur

	// make sure don't register twice this facebook account for this user.
	if (deck_river_get_networks_account('facebook_account', elgg_get_logged_in_user_guid(), $fbUserProfile['id'])) {
		$error[] = elgg_echo('deck_river:network:authorize:already_done');
	}

	if (!$error && $token) {

		$facebook_account = new ElggObject;
		$facebook_account->subtype = 'facebook_account';
		$facebook_account->access_id = 0;
		$facebook_account->user_id = $fbUserProfile['id'];
		$facebook_account->name = $fbUserProfile['name'];
		$facebook_account->username = $fbUserProfile['username'];
		$facebook_account->oauth_token = $token;

		echo elgg_view('page/elements/head');
		echo elgg_view('page/elements/foot');

		if ($facebook_account->save()) {
			// trigger authorization hook
			elgg_trigger_event('authorize', 'deck_river:facebook', $twitter_account);

			$facebook_account->time_created = time(); // Don't now why time_created is not filled
			$fb_guid = $facebook_account->getGUID();

			// add head and foot for js script
			echo elgg_view('page/elements/head');
			echo elgg_view('page/elements/foot');

			$account_output = json_encode(array(
				'network' => 'facebook',
				'network_box' => elgg_view_entity($facebook_account, array(
										'view_type' => 'in_network_box',
									)),
				'full' => '<li id="elgg-object-' . $fb_guid . '" class="elgg-item">' . elgg_view_entity($facebook_account) . '</li>',
				'code' => "elgg.deck_river.getFBGroups('{$facebook_account->user_id}', '{$token}', '{$fb_guid}');"
			));
			echo '<script type="text/javascript">$(document).ready(function() {elgg.deck_river.network_authorize(' . $account_output . ');});</script>';

		} else {
			$error[] = elgg_echo('deck_river:network:authorize:error');
		}

	}

	if ($error) {
		// add head and foot for js script
		echo elgg_view('page/elements/head');
		echo elgg_view('page/elements/foot');

		echo elgg_echo('deck_river:network:authorize:error');
		echo '<script type="text/javascript">$(document).ready(function() {authorizeError = '. json_encode($error) .';elgg.deck_river.network_authorize(false);});</script>';
	}
}


function deck_river_facebook_revoke($user_guid = null, $user_id = null, $echo = true) {
	if (!$user_guid) $user_guid = elgg_get_logged_in_user_guid();

	if ($user_guid && elgg_instanceof(get_entity($user_guid), 'user')) {

		$user_deck_river_accounts_in_wire = json_decode(get_private_setting($user_guid, 'user_deck_river_accounts_in_wire'), true);

		$entities = deck_river_get_networks_account('facebook_account', $user_guid, $user_id);

		foreach ($entities as $entity) {
			if ($entity->canEdit()) {
				// remove account from pinned accounts
				$user_deck_river_accounts_in_wire['position'] = array_diff($user_deck_river_accounts_in_wire['position'], array($entity->getGUID()));
				$user_deck_river_accounts_in_wire['pinned'] = array_diff($user_deck_river_accounts_in_wire['pinned'], array($entity->getGUID()));
				set_private_setting($user_guid, 'user_deck_river_accounts_in_wire', json_encode($user_deck_river_accounts_in_wire));

				if (get_readable_access_level($entity->access_id) == 'shared_network_acl') {
					delete_access_collection($entity->access_id);
				}

				// remove account
				$entity->delete();
			}
		}

		if ($echo && $entities) system_message(elgg_echo('deck_river:facebook:revoke:success'));
	} else {
		register_error(elgg_echo('deck_river:network:revoke:error'));
	}
}




