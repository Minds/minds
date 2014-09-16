<?php

global $CONFIG, $jsonexport;
$dbprefix = $CONFIG->dbprefix;

// Get callbacks
$params = get_input('params', 'false');
$time_method = get_input('time_method', 'false');
$time_posted = get_input('time_posted', 'false');

$jsonexport = array();

// detect network
if ($params && $method = $params['method']) {

	unset($params['method']);
	if (!$params['count']) $params['count'] = 30;

	if ($time_method == 'lower') {
		$params = array_merge($params, array(
										'since_id' => $time_posted+1 // +1 for not repeat first river item
				));
	} else if ($time_method == 'upper') {
		$params = array_merge($params, array(
										'since_id' => $time_posted-1 // -1 for not repeat last river item
				));
	}

	$twitter_consumer_key = elgg_get_plugin_setting('twitter_consumer_key', 'elgg-deck_river');
	$twitter_consumer_secret = elgg_get_plugin_setting('twitter_consumer_secret', 'elgg-deck_river');

	$accounts = deck_river_get_networks_account('twitter_account');
	$account = $accounts[0]; // @todo why the first ? Check limit rate and take the most free ?

	elgg_load_library('deck_river:twitter_async');
	$twitterObj = new EpiTwitter($twitter_consumer_key, $twitter_consumer_secret, $account->oauth_token, $account->oauth_token_secret);

	try {
		$result = call_user_func(array($twitterObj, $method), $params);
	} catch(Exception $e) {
		$result = json_decode($e->getMessage())->errors[0];
	}

	// check result
	if ($result->code == 200) {
		if ($method == 'get_usersShow') {
			$jsonexport = $result;
		} else {
			$jsonexport['column_type'] = $method;

			if ($method == 'get_searchTweets') {
				$resp = $result->__get('response');
				$resp = $resp['statuses'];
			} else {
				$resp = $result->__get('response');
			}

			if (!empty($resp)) {
				$jsonexport['results'] = $resp;
			} else {
				$jsonexport['results'] = '<table height="100%" width="100%"><tr><td class="helper">'. elgg_echo('deck_river:twitter:notweet') . '</td></tr></table>';
			}
		}
	} else {
		$key = 'deck_river:twitter:error:' . $result->code;
		if (elgg_echo($key) == $key) { // check if language string exist
			$jsonexport['column_error'] = elgg_echo('deck_river:twitter:error', array($result->code, $result->message));
		} else {
			$jsonexport['column_error'] = elgg_echo($key);
		}
		$jsonexport['results'] = '';
	}
}

echo json_encode($jsonexport);
