<?php
// FACEBOOK
$selected = $vars['selected'];
$column = $vars['column'];
$tab = $vars['tab'];

$class = ($selected != 'facebook') ? ' hidden' : '';
echo '<div class="tab facebook' . $class . '"><ul class="box-settings phm"><li>';

$user = elgg_get_logged_in_user_entity();
// get facebook account
$facebook_accounts = deck_river_get_networks_account('facebook_account', $user->guid, null, true);

function displayFacebookAccount($account, $phrase, $class = null) {
	$site_name =  elgg_get_site_entity() -> name;
	$facebook_user = $account -> name;

	if ($account -> icon) {// this is a group
		$link = 'groups/' . $account -> name;
		$limited = ' limited';
	} else if ($account -> parent_id) {// this is a page
		$link = 'pages/' . $account -> name . '/' . $account -> user_id;
		$limited = ' limited';
	} else {// this is a facebook user
		$link = $account -> username;
		$limited = '';
	}

	$facebook_avatar = $account -> icon ? $account -> icon : 'https://graph.facebook.com/' . $account -> user_id . '/picture';

	// User facebook block
	$img = elgg_view('output/img', array('src' => $facebook_avatar, 'alt' => $facebook_user, 'class' => 'facebook-user-info-popup info-popup', 'title' => $facebook_user, 'width' => '24', 'height' => '24', ));
	$facebook_name = '<div class="elgg-river-summary"><span class="facebook-user-info-popup info-popup" title="' . $account -> user_id . '">' . $facebook_user . '</span>';
	$facebook_name .= '<br/><span class="elgg-river-timestamp">';
	$facebook_name .= elgg_view('output/url', array('href' => 'http://facebook.com/' . $link, 'text' => 'http://facebook.com/' . $link, 'target' => '_blank', 'rel' => 'nofollow'));
	$facebook_name .= elgg_view('output/url', array('href'=>elgg_get_site_url().'authorize/facebook/'.$account->guid.'/refresh', 'text'=>' - refresh'));
	$facebook_name .= elgg_view('output/url', array('href'=>elgg_get_site_url().'authorize/facebook/'.$account->guid.'/revoke', 'text'=>' - revoke'));
	$facebook_name .= '</span></div>';
	$facebook_name = elgg_view_image_block($img, $facebook_name);

	return elgg_view_module('info', "<span class=\"elgg-river-timestamp$limited\">$phrase</span>", $facebook_name, array('class' => 'float ' . $class));
}

//$add_account = elgg_view('output/url', array('href' => '#', 'text' => '+', 'class' => 'add_social_network tooltip s t', 'title' => elgg_echo('deck_river:network:add:account')));
$add_account = elgg_view('output/url', array(
			'href' => '#',
			'text' => elgg_echo('Authorize'),
			'class' => 'elgg-button elgg-button-action mtm',
			'id' => 'authorize-facebook'
		));

$pages = array();
if (!$facebook_accounts || count($facebook_accounts) == 0) {
	// No account registred, send user off to validate account

	$body = elgg_echo('deck_river:facebook:columnsettings:request');
	$body .= $add_account;
	$output = elgg_view_module('featured', elgg_echo('deck_river:facebook:authorize:request:title', array($site_name)), $body, array('class' => 'mtl float'));

	$options_values = array(// override values
	'search' => elgg_echo('deck_river:facebook:search'), );

} else if (count($facebook_accounts) == 1) {
	// One account registred

	$output = $add_account . displayFacebookAccount($facebook_accounts[0], elgg_echo('deck_river:facebook:your_account', array($site_name)), 'mtl');
	$output .= elgg_view('input/hidden', array('name' => 'facebook[account_guid]', 'class' => 'in-module', 'value' => $facebook_accounts[0] -> getGUID(), 'data-username' => $facebook_account[0] -> name));
	
	foreach($facebook_accounts[0]->getPages() as $page){
		$pages[] = $page;
	}

} else {
	// more than one account
	
	echo '<label class="clearfloat float">' . elgg_echo('deck_river:facebook:choose:account') . '</label><br />';
	$accounts_name = array();
	foreach ($facebook_account as $account) {
		$accounts .= displayFacebookAccount($account, '', 'mts mbm multi ' . $account -> getGUID());
		if ($account -> icon) {// this is a group
			$accounts_name[$account -> getGUID()] = elgg_echo('river:group') . ' ' . $account -> name;
		} else if ($account -> parent_id) {// this is a page
			$accounts_name[$account -> getGUID()] = elgg_echo('deck_river:facebook:pages') . ' ' . $account -> name;
		} else {// this is a facebook user
			$accounts_name[$account -> getGUID()] = $account -> name;
		}
		foreach($account->getPages() as $page){
			$pages[] = $page;
		}
	}
	echo elgg_view('input/dropdown', array('name' => 'facebook[account_guid]', 'value' => $column->account_guid, 'class' => 'in-module', 'options_values' => $accounts_name)) . $add_account;
	echo $accounts;

}

$options_values = array( 'home' => elgg_echo('deck_river:facebook:feed:home'), 
						// 'home_fql' => elgg_echo('deck_river:facebook:feed:home_fql'), 
						 'feed' => elgg_echo('deck_river:facebook:feed'), 
						 'statuses' => elgg_echo('deck_river:facebook:feed:statuses'), 
						 //'links' => elgg_echo('deck_river:facebook:feed:links'), 
						 //'page' => elgg_echo('deck_river:facebook:feed:page'), 
						 //'search' => elgg_echo('deck_river:facebook:feed:search'), 
						);
foreach($pages as $page){
//	var_dump($page);
	$options_values['page/'.$page['id']] = 'Page Feed: '.$page['name'];
}

// select feed
echo '<label class="clearfloat float">' . elgg_echo('deck_river:type') . '</label><br />';
echo elgg_view('input/dropdown', array('name' => 'facebook[method]', 'value' => $selected == 'facebook' ? $column->method : 'home', 'class' => 'column-type mts clearfloat float', 'options_values' => $options_values));

// search input
echo '<li class="search-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:search') . '</label><br />';
echo elgg_view('input/text', array('name' => 'facebook[search]', 'value' => $user_river_column_options -> search));
echo '</li>';


// select page
echo '<li class="page-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:select:page') . '</label><br />';
echo elgg_view('input/text', array('name' => 'facebook[page_name]', 'placeholder' => elgg_echo('Entrez le nom de la page'), 'value' => $user_river_column_options -> page_name, 'data-original_value' => $user_river_column_options -> page_id));
echo elgg_view('input/hidden', array('name' => 'facebook[page_id]', 'value' => $user_river_column_options -> page_id));
echo '</li>';

echo $output;

echo '</li></ul></div>';
