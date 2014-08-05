<?php
/**
 * User's applications page
 */

// Only logged in users
gatekeeper();

$user = elgg_get_logged_in_user_entity();

// Make sure we don't open a security hole ...
if ((!elgg_get_page_owner_entity()) || (!elgg_get_page_owner_entity()->canEdit())) {
	elgg_set_page_owner_guid($user->getGUID());
}

elgg_set_context('settings');

$content = '';


// twitter

$twitter_consumer_key = elgg_get_plugin_setting('twitter_consumer_key', 'elgg-deck_river');
$twitter_consumer_secret = elgg_get_plugin_setting('twitter_consumer_secret', 'elgg-deck_river');
if ($twitter_consumer_key && $twitter_consumer_secret) {

	$twitter_accounts = deck_river_get_networks_account('twitter_account', $user->getGUID());

	$name = 'twitter elgg-module-network elgg-module-aside mvl float';
	$title = '<span class="network-icon twitter-icon gwfb pbm float"></span><span class="pls">' . elgg_echo('Twitter') . '</span>';
	$add_button = elgg_view('output/url', array(
		'href' => '#',
		'text' => elgg_echo('deck_river:twitter:authorize:request:button'),
		'class' => 'elgg-button elgg-button-action float-alt',
		'id' => 'authorize-twitter'
	));

	if (!empty($twitter_accounts)) {
		$content .= elgg_view_module(
			$name,
			$title . $add_button,
			elgg_view_entity_list($twitter_accounts), array(
				'class' => 'mtl',
			)
		);
	} else {
		$site_name = elgg_get_site_entity()->name;
		$content .= elgg_view_module(
			$name,
			$title,
			elgg_view_module(
				'featured',
				elgg_echo('deck_river:twitter:authorize:request:title', array($site_name)),
				elgg_echo('deck_river:twitter:add_network:request', array($site_name)) . $add_button,
				array('class' => 'mts float')
			)
		);
	}
}



// facebook

$facebook_app_id = elgg_get_plugin_setting('facebook_app_id', 'elgg-deck_river');
$facebook_app_secret = elgg_get_plugin_setting('facebook_app_secret', 'elgg-deck_river');
if ($facebook_app_id && $facebook_app_secret) {

	$facebook_accounts = deck_river_get_networks_account('facebook_account', $user->getGUID());

	$name = 'facebook elgg-module-network elgg-module-aside mvl float';
	$title = '<span class="network-icon facebook-icon gwfb pbm float"></span><span class="pls">' . elgg_echo('Facebook') . '</span>';
	$add_button = elgg_view('output/url', array(
		'href' => '#',
		'text' => elgg_echo('deck_river:facebook:authorize:request:button'),
		'class' => 'elgg-button elgg-button-action float-alt',
		'id' => 'authorize-facebook'
	));

	if (!empty($facebook_accounts)) {
		$content .= elgg_view_module(
			$name,
			$title . $add_button,
			elgg_view_entity_list($facebook_accounts), array(
				'class' => 'mtl',
			)
		);
	} else {
		$site_name = elgg_get_site_entity()->name;
		$content .= elgg_view_module(
			$name,
			$title . '</span>',
			elgg_view_module(
				'featured',
				elgg_echo('deck_river:facebook:authorize:request:title', array($site_name)),
				elgg_echo('deck_river:facebook:add_network:request', array($site_name)) . $add_button,
				array('class' => 'mts float')
			)
		);
	}
}



// shared account
$sharedWithMe = deck_river_get_shared_accounts();

if ($sharedWithMe) {
	$content .= elgg_view_module(
			'shared elgg-module-network elgg-module-aside mvl float',
			'<div class="pbs">' . elgg_echo('deck_river:shared_accounts') . '</div>',
			elgg_view_entity_list($sharedWithMe), array(
				'class' => 'mtl',
			)
		);
}


$title = elgg_echo('usersettings:authorize:applications');

elgg_push_breadcrumb(elgg_echo('settings'), 'settings/user/' . $user->username);
elgg_push_breadcrumb($title);

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);