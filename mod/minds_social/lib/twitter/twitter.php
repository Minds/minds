<?php

function minds_social_twitter_init(){

	session_start();

	$consumer['key'] = "ZKiXcclET9CpKly7OX6gA";
	$consumer['secret'] = "17ZLddnGgOywRKUGKf6mWFZ5XKyECWcZPoGgZ5q10Zw";
	
	return $consumer;
}
	
/**
 * Forwards the user to twitter to authenticate
 *
 * This includes the login URL as the callback
 */
function minds_social_twitter_forward($type = 'login') {

	$callback = elgg_normalize_url("social/twitter/".$type);
	$request_link = minds_social_twitter_authorize_url($callback);

	forward($request_link, 'minds_social');
}

/**
 * Allow users to login via Twitter
 */
function minds_social_twitter_login() {
	header("X-No-Client-Cache: 0", true);	
	$token = minds_social_twitter_access_token(get_input('oauth_verifier'));
	
	if (!isset($token['oauth_token']) || !isset($token['oauth_token_secret'])) {
		register_error(elgg_echo('twitter_api:authorize:error'));
	}

	$user = elgg_get_logged_in_user_entity();
	if($user){
		minds_social_twitter_auth($token);
		return true;
	}

	$consumer = minds_social_twitter_init();
        $api = new TwitterOAuth($consumer['key'], $consumer['secret'], $token['oauth_token'], $token['oauth_token_secret']);
        $api->host = "https://api.twitter.com/1.1/";
        $twitter = $api->get('account/verify_credentials');
	
	$guid = get_user_index_to_guid('twitter:name:'.$twitter->screen_name);	
	
	if(!$guid){
		$guid = get_user_index_to_guid('twitter:id:'.$twitter->id);
	}
	
	if($guid){
		$user = get_entity($guid, 'user');
	}
	
	if ($user) {
		login($user);
		system_message(elgg_echo('twitter_api:login:success'));			
		forward('news');
	} else {
		
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
				elgg_set_plugin_user_setting('twitter_name', $twitter->screen_name, $guid);
				elgg_set_plugin_user_setting('twitter_id', $twitter->id, $guid);
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
	
				$db = new minds\core\data\call('user_index_to_guid');
		        $db->insert('twitter:id:'.$twitter->id,  array($guid => time()));//move this into the user class
				$db->insert('twitter:name'.$twitter->name,  array($guid => time()));
	
				//Automatically subscribe user to the Minds Channel
				minds_subscribe_default(null,null,null, array('user'=>$new_user));
				login($new_user);			
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
function minds_social_twitter_auth($display = 'normal') {
	$token = minds_social_twitter_access_token(get_input('oauth_verifier'));

	$user = elgg_get_logged_in_user_entity();	
	if (!isset($token['oauth_token']) || !isset($token['oauth_token_secret'])) {
		register_error(elgg_echo('twitter_api:authorize:error'));
		forward('settings/plugins/'.$user->username, 'twitter_api');
	}

	elgg_unset_plugin_user_setting('twitter_name', $user->getGUID());
	elgg_unset_plugin_user_setting('twitter_id', $user->getGUID());
	elgg_unset_plugin_user_setting('minds_social_twitter_access_key', $user->getGUID());
	elgg_unset_plugin_user_setting('minds_social_twitter_access_secret', $user->getGUID());


	// register user's access tokens
	elgg_set_plugin_user_setting('twitter_name', $token['screen_name']);
	elgg_set_plugin_user_setting('twitter_id', $token['id']);
	elgg_set_plugin_user_setting('minds_social_twitter_access_key', $token['oauth_token']);
	elgg_set_plugin_user_setting('minds_social_twitter_access_secret', $token['oauth_token_secret']);
	
	// trigger authorization hook
	//elgg_trigger_plugin_hook('authorize', 'twitter_api', array('token' => $token));

	system_message(elgg_echo('minds_social:twitter:authsuccess'));
	
	if($display == 'popup'){
		echo '<script>window.close();</script>';
		exit;
	} else {
		forward('settings/plugins/'.$user->username);
	}
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
//	global $SESSION;

	$consumer = minds_social_twitter_init();

	// request tokens from Twitter
	$twitter = new TwitterOAuth($consumer['key'], $consumer['secret']);
	$token = $twitter->getRequestToken($callback);
	
	// save token in session for use after authorization
	$_SESSION['twitter_oauth_token'] = $token['oauth_token'];
	$_SESSION['twitter_oauth_token_secret'] =  $token['oauth_token_secret'];
	
	return $twitter->getAuthorizeURL($token['oauth_token'], $login);
}

/**
 * Returns the access token to use in twitter calls.
 *
 * @param unknown_type $oauth_verifier
 */
function minds_social_twitter_access_token($oauth_verifier = FALSE) {
//	global $SESSION;
	$consumer = minds_social_twitter_init();
//	var_dump($SESSION['twitter_oauth_token_secret']); exit;


// retrieve stored tokens
	$oauth_token = $_SESSION['twitter_oauth_token'];
	$oauth_token_secret = $_SESSION['twitter_oauth_token_secret'];
	//$SESSION->offsetUnset('twitter_api');
	//session_destroy();	
	// fetch an access token
	$api = new TwitterOAuth($consumer['key'], $consumer['secret'], $oauth_token, $oauth_token_secret);
	return $api->getAccessToken($oauth_verifier);
}

/**
 * Removes a link for the user
 *
 *
 */
function minds_social_twitter_remove() {
	$user = elgg_get_logged_in_user_entity();
	elgg_unset_plugin_user_setting('twitter_name', $user->getGUID());
	elgg_unset_plugin_user_setting('minds_social_twitter_access_key', $user->getGUID());
	elgg_unset_plugin_user_setting('minds_social_twitter_access_secret', $user->getGUID());
	forward('settings/plugins');
	return true;
}
