<?php

elgg_load_library('facebook');

/* Initialize the facebook object
 */
function minds_social_facebook_init(){
	$facebook = new Facebook(array(
	  'appId'  => '184865748231073',
	  'secret' => '26d5c6cd2ae8945e19b4b1bce2d9df9d',
	));
	
	return $facebook;
}

/* Begin the authentication process for facebook
 * Links a facebook account to a minds account
 */
function minds_social_facebook_auth(){
	$facebook = minds_social_facebook_init();
	
	if (!$session = $facebook->getUser()) {
		forward(REFERER);
	}
	
	$user = elgg_get_logged_in_user_entity();
	
	if(!$user){
		forward();
	}
	
	
	elgg_unset_plugin_user_setting('minds_social_facebook_uid', $user->getGUID());
	elgg_unset_plugin_user_setting('minds_social_facebook_access_token', $user->getGUID());
	

	//the info for that user doesnt match our facebook info so we update or create the info
	$data = $facebook->api('/me');
	
	//get our access token 
	$access_token = $facebook->getAccessToken();

	// register user's access tokens
	elgg_set_plugin_user_setting('minds_social_facebook_uid', $session);
	elgg_set_plugin_user_setting('minds_social_facebook_access_token', $access_token);
		
	system_message(elgg_echo('minds:social:facebook:authsuccess'));
		
	forward(REFERER);
	
}

