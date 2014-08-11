<?php
/*
 * Show modal popup to add networks accounts
 */

gatekeeper();

$site_name = elgg_get_site_entity()->name;

global $deck_networks;
foreach($deck_networks as $network){
		$body = '<h2>' . elgg_echo($network['name']) . '</h2>';
		$body .= '<ul style="list-style: disc;" class="pll">Authorize ' . $network['name'] . '</ul><br />';
		$body .= elgg_view('output/url', array(
			'href' => elgg_get_site_url().'authorize/'.$network['name'],
			'text' => elgg_echo('Authorize'),
			'class' => 'elgg-button elgg-button-action mtm',
			'id' => 'authorize-'.$network['name']
		));
		
	echo elgg_view_image_block('<div class="'.$network['name'].'-icon gwfb"></div>', $body, array(
		'class' => 'pam'
	));
	
}

// check if user has too many accounts
//if (deck_river_count_networks_account('all') >= elgg_get_plugin_setting('max_accounts', 'elgg-deck_river')) {
//	echo elgg_echo('deck_river:network:too_many_accounts', array($site_name));
//	return true;
//}


/*	$body = '<h2>' . elgg_echo('deck_river:twitter:authorize:request:title', array($site_name)) . '</h2>';
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
*/
