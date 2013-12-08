<?php
/*
 * Show modal popup to add networks accounts
 */

gatekeeper();

elgg_load_library('deck_river:authorize');
$site_name = elgg_get_site_entity()->name;

// check if user has too many accounts
if (deck_river_count_networks_account('all') >= elgg_get_plugin_setting('max_accounts', 'elgg-deck_river')) {
	echo elgg_echo('deck_river:network:too_many_accounts', array($site_name));
	return true;
}



// twitter
$twitter_consumer_key = elgg_get_plugin_setting('twitter_consumer_key', 'elgg-deck_river');
$twitter_consumer_secret = elgg_get_plugin_setting('twitter_consumer_secret', 'elgg-deck_river');

if ($twitter_consumer_key && $twitter_consumer_secret) {
	elgg_load_library('deck_river:twitter_async');
	$twitterObjUnAuth = new EpiTwitter($twitter_consumer_key, $twitter_consumer_secret);
	$twitterRequestUrl = $twitterObjUnAuth->getAuthenticateUrl();

	$body = '<h2>' . elgg_echo('deck_river:twitter:authorize:request:title', array($site_name)) . '</h2>';
	if ($twitterRequestUrl) {
		$body .= '<ul style="list-style: disc;" class="pll">' . elgg_echo('deck_river:twitter:add_network:request', array($site_name)) . '</ul><br />';
		$body .= elgg_view('output/url', array(
			'href' => $twitterRequestUrl,
			'text' => elgg_echo('deck_river:twitter:authorize:request:button'),
			'class' => 'elgg-button elgg-button-action mtm',
			'id' => 'authorize-twitter'
		));
	} else {
		$body .= elgg_echo('deck_river:twitter:authorize:servor_fail', array($site_name));
	}

	echo elgg_view_image_block('<div class="twitter-icon gwfb"></div>', $body, array(
		'class' => 'pam'
	));
}



// facebook
$facebook_app_id = elgg_get_plugin_setting('facebook_app_id', 'elgg-deck_river');
$facebook_app_secret = elgg_get_plugin_setting('facebook_app_secret', 'elgg-deck_river');

if ($facebook_app_id && $facebook_app_secret) {
	//elgg_load_library('deck_river:facebook_async');
	elgg_load_library('deck_river:facebook_sdk');
	$facebook = new Facebook(array(
		'appId'  => $facebook_app_id,
		'secret' => $facebook_app_secret,
		'cookie' => true
	));

	$loginUrl = $facebook->getLoginUrl(array(
		'redirect_uri' => (elgg_get_site_url() . 'authorize/facebook'),
		'scope' => deck_river_get_facebook_scope(),
	));

	$body = '<h2>' . elgg_echo('deck_river:facebook:authorize:request:title', array($site_name)) . '</h2>';
	if ($loginUrl) {
		$body .= '<ul style="list-style: disc;" class="pll">' . elgg_echo('deck_river:facebook:add_network:request', array($site_name)) . '</ul><br />';
		$body .= elgg_view('output/url', array(
			'href' => $loginUrl,
			'text' => elgg_echo('deck_river:facebook:authorize:request:button'),
			'class' => 'elgg-button elgg-button-action mtm',
			'id' => 'authorize-facebook'
		));
	} else {
		$body .= elgg_echo('deck_river:facebook:authorize:servor_fail', array($site_name));
	}

	echo elgg_view_image_block('<div class="facebook-icon gwfb"></div>', $body, array(
		'class' => 'pam'
	));
}

