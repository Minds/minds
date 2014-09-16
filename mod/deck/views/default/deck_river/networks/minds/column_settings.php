<?php
/**
 * Minds Nodes column settings page
 */
$selected = $vars['selected'];
$column = $vars['column'];
$tab = $vars['tab'];

$user = elgg_get_logged_in_user_entity();
 
$class = ($selected != 'minds') ? ' hidden' : '';
echo '<div class="tab minds' . $class . '"><ul class="box-settings phm"><li>';

// get twitter account
$accounts = deck_river_get_networks_account('minds_account', $user -> guid, null, true);
$account = $accounts[0];

function displayMindsAccount($account, $phrase, $class = null) {
	$site_name =  elgg_get_site_entity() -> name;
	$user = $account->name;
	$url = $account->node != 'local' ?: str_replace('http://', '', elgg_get_site_url());
	$avatar = "http://$url/icon/$account->guid/tiny";
	
	// User twitter block
	$img = elgg_view('output/img', array('src' => $avatar, 'alt' => $user, 'class' => 'twitter-user-info-popup info-popup', 'title' => $user, 'width' => '24', 'height' => '24', ));
	$name = '<div class="elgg-river-summary"><span class="twitter-user-info-popup info-popup" title="' . $user . '">' . $user . '</span>';
	$name .= '<br/><span class="elgg-river-timestamp">';
	$name .= elgg_view('output/url', array('href' => "http://$url/$account->username", 'text' => "http://$url/$account->user", 'target' => '_blank', 'rel' => 'nofollow'));
	$name .= '</span></div>';
	$name = elgg_view_image_block($img, $name);

	return elgg_view_module('info', '<span class="elgg-river-timestamp">' . $phrase . '</span>', $name, array('class' => 'float ' . $class));
}

$options_values = array(	
							'network' => elgg_echo('minds:news'), 
							'trending' => elgg_echo('minds:news:trending'), 		
						);

$add_account = elgg_view('output/url', array('href' => '#', 'text' => '+', 'class' => 'add_social_network tooltip s t', 'title' => elgg_echo('deck_river:network:add:account')));

/*if (!$account || count($account) == 0) {
	// No account registred, send user off to validate account

	$body = elgg_echo('deck_river:minds:columnsettings:request');
	$body .= elgg_view('output/url', array('href' => '#', 'text' => elgg_echo('deck_river:minds:authorize:request:button'), 'class' => 'add_social_network elgg-button elgg-button-action mtm', ));
	$output = elgg_view_module('featured', elgg_echo('deck_river:minds:authorize:request:title', array($site_name)), $body, array('class' => 'mtl float'));

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
*/
/**
 * for now we can only supports a single (local) node..
 */
if(!$accounts || count($accounts) == 0){
	$account = new ElggDeckMinds();
	$account->node = 'local'; // local should be default!
	$account->id = elgg_get_logged_in_user_entity()->guid;
	$account->name = elgg_get_logged_in_user_entity()->name;
	$account->username = elgg_get_logged_in_user_entity()->username;
	$account->save();
}

$output = $add_account . displayMindsAccount($account, elgg_echo('deck_river:minds:your_account', array($site_name)), 'mtl');
$output .= elgg_view('input/hidden', array('name' => 'minds[account_guid]', 'class' => 'in-module', 'value' => $account->getGUID(), 'data-screen_name' => $account -> screen_name));

// select feed
echo '<label class="clearfloat float">' . elgg_echo('deck_river:type') . '</label>';
echo elgg_view('input/dropdown', array('name' => 'minds[method]', 'value' => $column->method, 'class' => 'column-type mts clearfloat float', 'options_values' => $options_values));


echo $output;

echo '</li></ul></div>';

unset($output);
