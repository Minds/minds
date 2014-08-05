<?php
/**
 * Tumblr column settings page
 */
$selected = $vars['selected'];
$column = $vars['column'];
$tab = $vars['tab'];

$user = elgg_get_logged_in_user_entity();

$class = ($selected != 'tumblr') ? ' hidden' : '';
echo '<div class="tab tumblr' . $class . '"><ul class="box-settings phm"><li>';

// get tumblr account
$tumblr_account = deck_river_get_networks_account('tumblr_account', $user->guid, null, true);

function displayTumblrAccount($account, $phrase, $class = null) {
	$site_name =  elgg_get_site_entity()->name;
	$tumblr_user = $account->screen_name;
	$tumblr_avatar = "http://api.tumblr.com/v2/blog/{$tumblr_user}.tumblr.com/avatar/24";
	// $account->avatar,

	// User tumblr block
	$img = elgg_view('output/img', array('src' => $tumblr_avatar, 'alt' => $tumblr_user, 'class' => 'tumblr-user-info-popup info-popup', 'title' => $tumblr_user, 'width' => '24', 'height' => '24', ));
	$tumblr_name = '<div class="elgg-river-summary"><span class="tumblr-user-info-popup info-popup" title="' . $tumblr_user . '">' . $tumblr_user . '</span>';
	$tumblr_name .= '<br/><span class="elgg-river-timestamp">';
	$tumblr_name .= elgg_view('output/url', array('href' => "http://{$tumblr_user}.tumblr.com/", 'text' => "http://{$tumblr_user}.tumblr.com/", 'target' => '_blank', 'rel' => 'nofollow'));
	$tumblr_name .= '</span></div>';
	$tumblr_name = elgg_view_image_block($img, $tumblr_name);

	return elgg_view_module('info', '<span class="elgg-river-timestamp">' . $phrase . '</span>', $tumblr_name, array('class' => 'float ' . $class));
}

$options_values = array(	//'get_searchTweets' => elgg_echo('deck_river:tumblr:feed:search:tweets'),
							//'get_searchTweets-popular' => elgg_echo('deck_river:tumblr:feed:search:popular'),
							'user/dashboard' => elgg_echo('deck_river:tumblr:dashboard'),
							'get_statusesUser_timeline' => elgg_echo('deck_river:tumblr:feed:user'),
							 //'get_listsStatuses' => elgg_echo('deck_river:tumblr:list'),
							 //'get_direct_messages' => elgg_echo('deck_river:tumblr:feed:dm:recept'),
							 //'get_direct_messagesSent' => elgg_echo('deck_river:tumblr:feed:dm:sent'),
							 //'get_favoritesList' => elgg_echo('deck_river:tumblr:feed:favorites')
						);

//$add_account = elgg_view('output/url', array('href' => '#', 'text' => '+', 'class' => 'add_social_network tooltip s t', 'title' => elgg_echo('deck_river:network:add:account')));
$add_account = elgg_view('output/url', array(
			'href' => elgg_get_site_url().'authorize/tumblr',
			'text' => elgg_echo('Authorize'),
			'class' => 'elgg-button elgg-button-action mtm',
			'id' => 'authorize-tumblr'
		));


if (!$tumblr_account || count($tumblr_account) == 0) {
	// No account registred, send user off to validate account

	$body = elgg_echo('deck_river:tumblr:columnsettings:request');
	$body .= $add_account;
	$output = elgg_view_module('featured', elgg_echo('deck_river:tumblr:authorize:request:title', array($site_name)), $body, array('class' => 'mtl float'));

	$options_values = array(// override values
	'get_searchTweets' => elgg_echo('deck_river:tumblr:feed:search:tweets'), 'get_searchTweets-popular' => elgg_echo('deck_river:tumblr:feed:search:popular'), );

} else if (count($tumblr_account) == 1) {
	// One account registred

	$output = $add_account . displayTumblrAccount($tumblr_account[0], elgg_echo('deck_river:tumblr:your_account', array($site_name)), 'mtl');
	$output .= elgg_view('input/hidden', array('name' => 'tumblr[account_guid]', 'class' => 'in-module', 'value' => $tumblr_account[0]->getGUID(), 'data-screen_name' => $tumblr_account[0]->screen_name));

} else {
	// more than one account

	if (!isset($user_river_column_options->account))
		$user_river_column_options->account = $tumblr_account[0]->getGUID();
	echo '<label  class="clearfloat float">' . elgg_echo('deck_river:tumblr:choose:account') . '</label><br />';
	foreach ($tumblr_account as $account) {
		$accounts .= displayTumblrAccount($account, '', 'mtm mbs multi ' . $account->getGUID());
		$accounts_name[$account->getGUID()] = $account->screen_name;
	}
	echo elgg_view('input/dropdown', array('name' => 'tumblr[account_guid]', 'value' => $accounts_name[$account->getGUID()], 'class' => 'in-module', 'options_values' => $accounts_name)) . $add_account;
	echo $accounts;

}

// select feed
echo '<label class="clearfloat float">' . elgg_echo('deck_river:type') . '</label>';
echo elgg_view('input/dropdown', array('name' => 'tumblr[method]', 'value' => $selected == 'tumblr' ? $column->method : 'tumblr:search/tweets', 'class' => 'column-type mts clearfloat float', 'options_values' => $options_values));

echo '<li class="get_searchTweets-options get_searchTweets-popular-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:search') . '</label><br />';
echo elgg_view('input/text', array('name' => 'tumblr-search', 'value' => $user_river_column_options->search));
echo '</li>';

echo '<li class="get_listsStatuses-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:tumblr:lists') . '</label><br />';
echo elgg_view('input/dropdown', array('name' => 'tumblr-lists', 'value' => $user_river_column_options->list_id, 'options_values' => array($user_river_column_options->list_id => $user_river_column_options->list_name), 'class' => 'float')) . '<div class="response-loader hidden float" style="margin: 1px 0px 0px 30px;"></div>';
echo '</li>';

echo $output;

echo '</li></ul></div>';

unset($output);
