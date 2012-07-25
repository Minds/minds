<?php

function minds_social_twitter_init(){
	
	$consumer['key'] = "ZKiXcclET9CpKly7OX6gA";
	$consumer['secret'] = "17ZLddnGgOywRKUGKf6mWFZ5XKyECWcZPoGgZ5q10Zw";
	
	return $consumer;
}
	
/**
 * Forwards the user to twitter to authenticate
 *
 * This includes the login URL as the callback
 */
function minds_social_twitter_forward() {

	$callback = elgg_normalize_url("social/twitter/auth");
	$request_link = minds_social_twitter_authorize_url($callback);

	forward($request_link, 'minds_social');
}

/**
 * User-initiated Twitter authorization
 *
 * Callback action from Twitter registration. Registers a single Elgg user with
 * the authorization tokens. Will revoke access from previous users when a
 * conflict exists.
 *
 * Depends upon {@link twitter_api_get_authorize_url} being called previously
 * to establish session request tokens.
 */
function minds_social_twitter_auth() {
	
	$token = minds_social_twitter_access_token();
	if (!isset($token['oauth_token']) || !isset($token['oauth_token_secret'])) {
		register_error(elgg_echo('twitter_api:authorize:error'));
		forward('settings/plugins', 'twitter_api');
	}

	$user = elgg_get_logged_in_user_entity();
	elgg_unset_plugin_user_setting('twitter_name', $user->getGUID());
	elgg_unset_plugin_user_setting('minds_social_twitter_access_key', $user->getGUID());
	elgg_unset_plugin_user_setting('minds_social_twitter_access_secret', $user->getGUID());


	// register user's access tokens
	elgg_set_plugin_user_setting('twitter_name', $token['screen_name']);
	elgg_set_plugin_user_setting('minds_social_twitter_access_key', $token['oauth_token']);
	elgg_set_plugin_user_setting('minds_social_twitter_access_secret', $token['oauth_token_secret']);
	
	// trigger authorization hook
	//elgg_trigger_plugin_hook('authorize', 'twitter_api', array('token' => $token));

	system_message(elgg_echo('minds_social:twitter:authsuccess'));
	forward('settings/plugins');
}

/**
 * Gets the url to authorize a user.
 *
 * @param string $callback The callback URL
 */
function minds_social_twitter_authorize_url($callback = NULL, $login = true) {
	global $SESSION;

	$consumer = minds_social_twitter_init();

	// request tokens from Twitter
	$twitter = new TwitterOAuth($consumer['key'], $consumer['secret']);
	$token = $twitter->getRequestToken($callback);

	// save token in session for use after authorization
	$SESSION['twitter_api'] = array(
		'oauth_token' => $token['oauth_token'],
		'oauth_token_secret' => $token['oauth_token_secret'],
	);

	return $twitter->getAuthorizeURL($token['oauth_token'], $login);
}

/**
 * Returns the access token to use in twitter calls.
 *
 * @param unknown_type $oauth_verifier
 */
function minds_social_twitter_access_token($oauth_verifier = FALSE) {
	global $SESSION;
	
	$consumer = minds_social_twitter_init();

	// retrieve stored tokens
	$oauth_token = $SESSION['twitter_api']['oauth_token'];
	$oauth_token_secret = $SESSION['twitter_api']['oauth_token_secret'];
	$SESSION->offsetUnset('twitter_api');

	// fetch an access token
	$api = new TwitterOAuth($consumer['key'], $consumer['secret'], $oauth_token, $oauth_token_secret);
	return $api->getAccessToken($oauth_verifier);
}
