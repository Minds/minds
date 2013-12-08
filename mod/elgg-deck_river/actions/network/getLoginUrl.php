<?php
/**
 * Action to send app token access for facebook or twitter.
 */

action_gatekeeper();

$network = get_input('network');

$user = elgg_get_logged_in_user_entity();

if ($network == 'twitter' && $user) {
	elgg_load_library('deck_river:twitter_async');

	$twitter_consumer_key = elgg_get_plugin_setting('twitter_consumer_key', 'elgg-deck_river');
	$twitter_consumer_secret = elgg_get_plugin_setting('twitter_consumer_secret', 'elgg-deck_river');

	try {
		$twitterObjUnAuth = new EpiTwitter($twitter_consumer_key, $twitter_consumer_secret);
		echo $twitterObjUnAuth->getAuthenticateUrl();
	} catch(Exception $e) {
		echo false;
	}

} else if ($network == 'facebook' && $user) {
	elgg_load_library('deck_river:facebook_sdk');
	elgg_load_library('deck_river:authorize');

	$facebook_app_id = elgg_get_plugin_setting('facebook_app_id', 'elgg-deck_river');
	$facebook_app_secret = elgg_get_plugin_setting('facebook_app_secret', 'elgg-deck_river');

	try {
		$facebook = new Facebook(array(
			'appId'  => $facebook_app_id,
			'secret' => $facebook_app_secret,
			'cookie' => true
		));
		echo $facebook->getLoginUrl(array(
			'redirect_uri' => (elgg_get_site_url() . 'authorize/facebook'),
			'scope' => deck_river_get_facebook_scope(),
		));
	} catch(FacebookApiException $e) {
		echo false;
	}

} else {

	register_error(elgg_echo('deck_river:twitter:error'));

}
