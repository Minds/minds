<?php
/**
 * Twitter column settings page
 */
$selected = $vars['selected'];
$column = $vars['column'];
$tab = $vars['tab'];

$user = elgg_get_logged_in_user_entity();
 
$class = ($selected != 'twitter') ? ' hidden' : '';
echo '<div class="tab twitter' . $class . '"><ul class="box-settings phm"><li>';

// get twitter account
$twitter_account = deck_river_get_networks_account('twitter_account', $user -> guid, null, true);

function displayTwitterAccount($account, $phrase, $class = null) {
	$site_name =  elgg_get_site_entity() -> name;
	$twitter_user = $account -> screen_name;
	$twitter_avatar = 'http://twitter.com/api/users/profile_image/' . $account -> screen_name . '?size=mini';
	// $account->avatar,

	// User twitter block
	$img = elgg_view('output/img', array('src' => $twitter_avatar, 'alt' => $twitter_user, 'class' => 'twitter-user-info-popup info-popup', 'title' => $twitter_user, 'width' => '24', 'height' => '24', ));
	$twitter_name = '<div class="elgg-river-summary"><span class="twitter-user-info-popup info-popup" title="' . $twitter_user . '">' . $twitter_user . '</span>';
	$twitter_name .= '<br/><span class="elgg-river-timestamp">';
	$twitter_name .= elgg_view('output/url', array('href' => 'http://twitter.com/' . $twitter_user, 'text' => 'http://twitter.com/' . $twitter_user, 'target' => '_blank', 'rel' => 'nofollow'));
	$twitter_name .= '</span></div>';
	$twitter_name = elgg_view_image_block($img, $twitter_name);

	return elgg_view_module('info', '<span class="elgg-river-timestamp">' . $phrase . '</span>', $twitter_name, array('class' => 'float ' . $class));
}

$options_values = array(	//'get_searchTweets' => elgg_echo('deck_river:twitter:feed:search:tweets'), 
							//'get_searchTweets-popular' => elgg_echo('deck_river:twitter:feed:search:popular'), 
							'get_statusesHome_timeline' => elgg_echo('deck_river:twitter:feed:home'), 
							'get_statusesMentions_timeline' => elgg_echo('river:mentions'), 
							'get_statusesUser_timeline' => elgg_echo('deck_river:twitter:feed:user'),
							 //'get_listsStatuses' => elgg_echo('deck_river:twitter:list'), 
							 //'get_direct_messages' => elgg_echo('deck_river:twitter:feed:dm:recept'), 
							 //'get_direct_messagesSent' => elgg_echo('deck_river:twitter:feed:dm:sent'), 
							 //'get_favoritesList' => elgg_echo('deck_river:twitter:feed:favorites')
						);

//$add_account = elgg_view('output/url', array('href' => '#', 'text' => '+', 'class' => 'add_social_network tooltip s t', 'title' => elgg_echo('deck_river:network:add:account')));
$add_account = elgg_view('output/url', array(
			'href' => elgg_get_site_url().'authorize/twitter',
			'text' => elgg_echo('Authorize'),
			'class' => 'elgg-button elgg-button-action mtm',
			'id' => 'authorize-twitter'
		));


if (!$twitter_account || count($twitter_account) == 0) {
	// No account registred, send user off to validate account

	$body = elgg_echo('deck_river:twitter:columnsettings:request');
	$body .= $add_account;
	$output = elgg_view_module('featured', elgg_echo('deck_river:twitter:authorize:request:title', array($site_name)), $body, array('class' => 'mtl float'));

	$options_values = array(// override values
	'get_searchTweets' => elgg_echo('deck_river:twitter:feed:search:tweets'), 'get_searchTweets-popular' => elgg_echo('deck_river:twitter:feed:search:popular'), );

} else if (count($twitter_account) == 1) {
	// One account registred

	$output = $add_account . displayTwitterAccount($twitter_account[0], elgg_echo('deck_river:twitter:your_account', array($site_name)), 'mtl');
	$output .= elgg_view('input/hidden', array('name' => 'twitter[account_guid]', 'class' => 'in-module', 'value' => $twitter_account[0] -> getGUID(), 'data-screen_name' => $twitter_account[0] -> screen_name));

} else {
	// more than one account

	if (!isset($user_river_column_options -> account))
		$user_river_column_options -> account = $twitter_account[0] -> getGUID();
	echo '<label  class="clearfloat float">' . elgg_echo('deck_river:twitter:choose:account') . '</label><br />';
	foreach ($twitter_account as $account) {
		$accounts .= displayTwitterAccount($account, '', 'mtm mbs multi ' . $account -> getGUID());
		$accounts_name[$account -> getGUID()] = $account -> screen_name;
	}
	echo elgg_view('input/dropdown', array('name' => 'twitter[account_guid]', 'value' => $accounts_name[$account -> getGUID()], 'class' => 'in-module', 'options_values' => $accounts_name)) . $add_account;
	echo $accounts;

}

// select feed
echo '<label class="clearfloat float">' . elgg_echo('deck_river:type') . '</label>';
echo elgg_view('input/dropdown', array('name' => 'twitter[method]', 'value' => $selected == 'twitter' ? $column->method : 'twitter:search/tweets', 'class' => 'column-type mts clearfloat float', 'options_values' => $options_values));

echo '<li class="get_searchTweets-options get_searchTweets-popular-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:search') . '</label><br />';
echo elgg_view('input/text', array('name' => 'twitter-search', 'value' => $user_river_column_options -> search));
echo '</li>';

echo '<li class="get_listsStatuses-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:twitter:lists') . '</label><br />';
echo elgg_view('input/dropdown', array('name' => 'twitter-lists', 'value' => $user_river_column_options -> list_id, 'options_values' => array($user_river_column_options -> list_id => $user_river_column_options -> list_name), 'class' => 'float')) . '<div class="response-loader hidden float" style="margin: 1px 0px 0px 30px;"></div>';
echo '</li>';

echo $output;

echo '</li></ul></div>';

unset($output);
