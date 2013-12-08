<?php
/**
 * Action for facebook
 */

$account_guid = (int) get_input('facebook_account');
$method = (string) get_input('method');
$options = (array) get_input('options');

$account = get_entity($account_guid);
$user = elgg_get_logged_in_user_entity();

action_gatekeeper();

if ($method && $account->getSubtype() == 'facebook_account' && $account->getOwnerGUID() == $user->getGUID()) {
	elgg_load_library('deck_river:facebook_sdk');
	$facebook = new Facebook(array(
		'appId'  => elgg_get_plugin_setting('facebook_app_id', 'elgg-deck_river'),
		'secret' => elgg_get_plugin_setting('facebook_app_secret', 'elgg-deck_river')
	));
	$facebook->setAccessToken($account->oauth_token);

	try {
		$result = $facebook->api($account->user_id . '/' . $method, 'post', $options);
	} catch(FacebookApiException $e) {
		$result = json_decode($e);
	}

	if ($result) {
		$jsonexport['result'] = $result;
	} else {
		register_error(elgg_echo('deck_river:facebook:error'));
		$jsonexport['result'] = '';
	}

	echo json_encode($jsonexport);

} else {
	register_error(elgg_echo('deck_river:facebook:error'));
}
