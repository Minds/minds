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

	$callback = elgg_normalize_url("social/twitter/login");
	$request_link = minds_social_twitter_authorize_url($callback);

	forward($request_link, 'minds_social');
}

/**
 * Allow users to login via Twitter
 */
function minds_social_twitter_login() {
	
	$token = minds_social_twitter_access_token();
	
	if (!isset($token['oauth_token']) || !isset($token['oauth_token_secret'])) {
		register_error(elgg_echo('twitter_api:authorize:error'));
	}

	$user = elgg_get_logged_in_user_entity();
	if($user){
		minds_social_twitter_auth($token);
		return true;
	}
	
	// attempt to find user and log them in.
	// else, create a new user.
	$options = array(
		'type' => 'user',
		'plugin_user_setting_name_value_pairs' => array(
			'twitter_name' => $token['screen_name'],
			'minds_social_twitter_access_key' => $token['oauth_token'],
			'minds_social_twitter_access_secret' => $token['oauth_token_secret'],
		),
		'plugin_user_setting_name_value_pairs_operator' => 'OR',
		'limit' => 0
	);

	$users = elgg_get_entities_from_plugin_user_settings($options);
	
	if ($users) {
		if (count($users) == 1 && login($users[0])) {
			system_message(elgg_echo('twitter_api:login:success'));			
		} else {
			register_error(elgg_echo('twitter_api:login:error'));
		}
		
		forward('news');
	} else {
		
		$consumer = minds_social_twitter_init();
		$api = new TwitterOAuth($consumer['key'], $consumer['secret'], $token['oauth_token'], $token['oauth_token_secret']);
		$twitter = $api->get('account/verify_credentials');
		
		$username = $twitter->screen_name;
			
		if(!$username){
			$username = str_replace(' ', '', strtolower($twitter->name));
		}
		while (get_user_by_username($username)){
			$username = $username . '.' . rand(0,100);
		}
		
		$name = $twitter->name;
		$email = $username . '@twitter.minds.com';
		$password = generate_random_cleartext_password();
		
		$guid = register_user($username, $password, $name, $email);
		
		if($guid) {
				elgg_clear_sticky_form('register');
				
				$new_user = get_entity($guid);
	
				// set twitter services tokens
				elgg_set_plugin_user_setting('twitter_name', $token['screen_name'], $guid);
				elgg_set_plugin_user_setting('minds_social_twitter_access_key', $token['oauth_token'], $guid);
				elgg_set_plugin_user_setting('minds_social_twitter_access_secret', $token['oauth_token_secret'], $guid);
			
				// pull in Twitter icon
				minds_social_twitter_update_user_avatar($new_user, $twitter->profile_image_url);

				//trigger the validator plugins
				$params = array(
					'user' => $new_user,
					'password' => $password,
					'friend_guid' => $friend_guid,
					'invitecode' => $invitecode
				);
	
				// @todo should registration be allowed no matter what the plugins return?
				if (!elgg_trigger_plugin_hook('register', 'user', $params, TRUE)) {
					$new_user->delete();
					// @todo this is a generic messages. We could have plugins
					// throw a RegistrationException, but that is very odd
					// for the plugin hooks system.
					throw new RegistrationException(elgg_echo('registerbad'));
				}
				forward();			
		}
	}
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
function minds_social_twitter_auth($token) {
	
	$token = $token != NULL ? $token : minds_social_twitter_access_token();
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
 * Pull in the latest avatar from twitter.
 *
 * @param ElggUser $user
 * @param string   $file_location
 */
function minds_social_twitter_update_user_avatar($user, $file_location) {
	// twitter's images have a few suffixes:
	// _normal
	// _reasonably_small
	// _mini
	// the twitter app here returns _normal.  We want standard, so remove the suffix.
	// @todo Should probably check that it's an image file.
	$file_location = str_replace('_normal.jpeg', '.jpeg', $file_location);

	$icon_sizes = elgg_get_config('icon_sizes');

	$filehandler = new ElggFile();
	$filehandler->owner_guid = $user->getGUID();
	foreach ($icon_sizes as $size => $dimensions) {
		$image = get_resized_image_from_existing_file(
			$file_location,
			$dimensions['w'],
			$dimensions['h'],
			$dimensions['square']
		);

		$filehandler->setFilename("profile/$user->guid$size.jpg");
		$filehandler->open('write');
		$filehandler->write($image);
		$filehandler->close();
	}
	
	// update user's icontime
	$user->icontime = time();
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
