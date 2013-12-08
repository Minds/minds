<?php
/**
 * Action for adding a wire post
 */

// don't filter since we strip and filter escapes some characters
$body = get_input('body', '', false);
$networks = (array) get_input('networks');
$method = get_input('method', false);
$link_url = get_input('link_url', false);
$link_name = get_input('link_name', false);
$link_description = get_input('link_description', false);
$link_picture = get_input('link_picture', false);

$user = elgg_get_logged_in_user_entity();

// make sure the post isn't blank
if (empty($body)) {
	register_error(elgg_echo("thewire:blank"));
} else if (!$networks) {
	register_error(elgg_echo("thewire:nonetwork"));

} else {
	array_unique($networks);

	if (count($networks) > 5) {
		register_error(elgg_echo("thewire:error"));
		return false;
	}

	// no html tags allowed so we escape
	$body = htmlspecialchars($body, ENT_NOQUOTES, 'UTF-8');

	foreach ($networks as $network) {
		if ($network == $user->getGUID()) { // network is elgg

			// only 140 characters allowed without links
			$body_temp = preg_replace('/https?:\/\/.*(?:\s|$)/Um', '', trim($body));
			$links_length = mb_strlen(trim($body)) - mb_strlen($body_temp);
			$body = elgg_substr(trim($body), 0, 140+$links_length);

			$parent_guid = (int) get_input('elgg_parent', false);
			$params = array(
				'body' => $body,
				'owner_guid' => $user->guid,
				'access_id' => ACCESS_PUBLIC,
				'parent_guid' => $parent_guid,
				'method' => $method
			);

			$params = elgg_trigger_plugin_hook('deck-river', 'message:before_create:elgg', $params, $params);
			if ($params !== false) { // plugin can cancel river create by sending false

				$guid = deck_river_thewire_save_post($params);
				if (!$guid) {
					register_error(elgg_echo("thewire:error"));
				} else {
					// Send response to original poster if not already registered to receive notification
					if ($parent_guid) {
						$parent_entity = get_entity($parent_guid);
						if ($parent_entity && $parent_entity->getSubtype() == 'thewire') {
							deck_river_thewire_send_response_notification($guid, $parent_guid, $user);
							$parent_owner_guid = get_entity($parent_guid)->getOwnerGUID();
						}
					}
					// send @mention
					foreach (deck_river_thewire_get_users($body) as $user_mentioned) {
						if ($user_mentioned->guid != $user->guid // don't send mention to owner of the message
							&& $user_mentioned->guid != $parent_owner_guid) // already send mail with send response notification
						deck_river_thewire_send_mention_notification($guid, $user_mentioned);
					}
					system_message(elgg_echo('thewire:posted:'.rand(0,9)));
				}
			}

		} else {
			$network_entity = get_entity($network);
			if ($network_entity) {

				// twitter
				if ($network_entity->getSubtype() == 'twitter_account' && has_access_to_entity($network_entity)) {
					elgg_load_library('deck_river:twitter_async');
					$twitter_consumer_key = elgg_get_plugin_setting('twitter_consumer_key', 'elgg-deck_river');
					$twitter_consumer_secret = elgg_get_plugin_setting('twitter_consumer_secret', 'elgg-deck_river');
					$twitterObj = new EpiTwitter($twitter_consumer_key, $twitter_consumer_secret, $network_entity->oauth_token, $network_entity->oauth_token_secret);

					//$body = elgg_substr($body, 0, 140); // only 140 characters allowed

					// parse message to replace !group by #group
					$body = preg_replace(
						'/(^|[^\w])!([\p{L}\p{Nd}._]+)/u',
						'$1#$2',
						$body);

					// post to twitter
					$parent_guid = (int) get_input('twitter_parent', false);
					try {
						if (preg_match('/^(?:d|dm)\s+([a-z0-9-_@]+)\s*(.*)/i', $body, $matches)) { // direct message
							if (!$matches[2]) {
								register_error(elgg_echo('deck_river:message:blank'));
								return true;
							}
							$result = $twitterObj->post_direct_messagesNew(array('text' => $matches[2], 'screen_name' => str_replace('@', '', $matches[1])));
						} else {
							if ($parent_guid) { // response to a tweet with in_reply_to_status_id
								$result = $twitterObj->post_statusesUpdate(array('status' => $body, 'in_reply_to_status_id' => $parent_guid));
							} else {
								$result = $twitterObj->post_statusesUpdate(array('status' => $body));
							}
						}
					} catch(Exception $e) {
						$result = json_decode($e->getMessage())->errors[0];
					}

					// check result
					if ($result->code == 200) {
						system_message(elgg_echo('deck_river:twitter:posted'));
					} else {
						$key = 'deck_river:twitter:post:error:' . $result->code;
						if (elgg_echo($key) == $key) { // check if language string exist
							register_error(elgg_echo('deck_river:twitter:post:error', array($result->code, $result->message)));
						} else {
							register_error(elgg_echo($key));
						}
					}
				}

				// facebook
				if ($network_entity->getSubtype() == 'facebook_account' && has_access_to_entity($network_entity)) {
					elgg_load_library('deck_river:facebook_sdk');
					$facebook = new Facebook(array(
						'appId'  => elgg_get_plugin_setting('facebook_app_id', 'elgg-deck_river'),
						'secret' => elgg_get_plugin_setting('facebook_app_secret', 'elgg-deck_river')
					));
					$facebook->setAccessToken($network_entity->oauth_token);

					$params = array(
						'message' => $body
					);

					if ($link_url && !in_array($link_url, array('null', 'undefined'))) {
						$params['link'] = $params['caption'] = $link_url;
					}
					if ($link_name && !in_array($link_name, array('null', 'undefined'))) $params['name'] = $link_name;
					if ($link_description && !in_array($link_description, array('null', 'undefined'))) $params['description'] = $link_description;
					if ($link_picture && !in_array($link_picture, array('null', 'undefined'))) $params['picture'] = $link_picture;
					//'privacy' => json_encode(array('value' => 'EVERYONE')) // https://developers.facebook.com/docs/reference/api/privacy-parameter/

					$share = get_input('facebook_parent', false);
					if ($share) {
						$params['link'] = 'https://www.facebook.com/' . preg_replace('/_/', '/posts/', $share);
						$params['feature'] = 'share';
					}

					try {
						$result = $facebook->api($network_entity->user_id . '/feed', 'post', $params);
					} catch(FacebookApiException $e) {
						$result = $e;
					}

					if (is_array($result) && $result['id']) {
						system_message(elgg_echo('deck_river:facebook:posted', array("https://facebook.com/{$result['id']}")));
					} else {
						register_error(elgg_echo('deck_river:facebook:error:code', array($result)));
					}

				}

			} else {
				register_error(elgg_echo("thewire:error"));
			}
		}
	}

}
