<?php

$tab = get_input('tab');
$column = get_input('column');
$submit = get_input('submit');

if (!$submit || !$tab || !$column) {
	return;
}

// Get the settings of the current column of the current user
$owner = elgg_get_logged_in_user_guid();
$user_river_options = json_decode(get_private_setting($owner, 'deck_river_settings'), true);

// reset some settings
$user_river_options[$tab][$column]['direct'] = false;

$return = array();
if ($submit == 'delete') {
	unset($user_river_options[$tab][$column]);
	$return['action'] = 'delete';
	$return['column'] = $column;
} else if ($submit == 'elgg') {
	$type = get_input('type');
	$search = get_input('search');
	$group = get_input('group');
	$types_filter = get_input('filters_types');
	$subtypes_filter = get_input('filters_subtypes');

	if (!$type) {
		return;
	}

	switch ($type) {
		case 'all':
			$user_river_options[$tab][$column]['title'] = 'river:all';
			$user_river_options[$tab][$column]['subtitle'] = '';
			break;
		case 'friends':
			$user_river_options[$tab][$column]['title'] = 'river:timeline';
			$user_river_options[$tab][$column]['subtitle'] = 'river:timeline:definition';
			break;
		case 'mine':
			$user_river_options[$tab][$column]['title'] = 'river:mine';
			$user_river_options[$tab][$column]['subtitle'] = get_entity($owner)->name;
			break;
		case 'mention':
			$user_river_options[$tab][$column]['title'] = '@' . get_entity($owner)->name;
			$user_river_options[$tab][$column]['subtitle'] = 'river:mentions';
			break;
		case 'groups':
			$user_river_options[$tab][$column]['title'] = 'river:groups_timeline';
			$user_river_options[$tab][$column]['subtitle'] = 'river:groups_activity';
			break;
		case 'group':
			$user_river_options[$tab][$column]['group'] = $group;
			$user_river_options[$tab][$column]['title'] = get_entity($group)->name;
			$user_river_options[$tab][$column]['subtitle'] = 'river:group_activity';
			break;
		case 'group_mention':
			$user_river_options[$tab][$column]['group'] = $group;
			$user_river_options[$tab][$column]['title'] = '!' . get_entity($group)->name;
			$user_river_options[$tab][$column]['subtitle'] = 'river:group_mentions';
			break;
		case 'search':
			$user_river_options[$tab][$column]['search'] = explode(' ', sanitise_string($search));
			$user_river_options[$tab][$column]['title'] = $search;
			$user_river_options[$tab][$column]['subtitle'] = elgg_echo('river:search', array(elgg_get_site_entity()->name));
			break;
		default:
			$params = array('owner' => $owner, 'query' => 'settings');
			$hook = elgg_trigger_plugin_hook('deck-river', "column:$type", $params);
			$user_river_options[$tab][$column] = array_merge($user_river_options[$tab][$column], $hook);
			break;
	}

	// allow plugin break here
	if (!$hook) {

		// merge keys defined by admin
		$keys_to_merge = explode(',', elgg_get_plugin_setting('keys_to_merge', 'elgg-deck_river'));
		foreach ($keys_to_merge as $key => $value ) {
			$key_master = explode('=', $value);
			foreach ($types_filter as $k => $v) {
				if ($v == $key_master[0]) $types_filter[] = $key_master[1];
			}
			foreach ($subtypes_filter as $k => $v) {
				if ($v == $key_master[0]) $subtypes_filter[] = $key_master[1];
			}
		}

		// filter
		if ($types_filter == '0') $types_filter = ''; // in case no checkbox checked or All
		if ($subtypes_filter == '0') $subtypes_filter = ''; // in case no checkbox checked
		if (in_array('All', $types_filter)) {
			unset($user_river_options[$tab][$column]['types_filter']);
			unset($user_river_options[$tab][$column]['subtypes_filter']);
		} elseif ($types_filter == 0) {
			unset($user_river_options[$tab][$column]['types_filter']);
			$user_river_options[$tab][$column]['subtypes_filter'] = $subtypes_filter;
		} elseif ($subtypes_filter == 0) {
			unset($user_river_options[$tab][$column]['subtypes_filter']);
			$user_river_options[$tab][$column]['types_filter'] = $types_filter;
		} else {
			$user_river_options[$tab][$column]['types_filter'] = $types_filter;
			$user_river_options[$tab][$column]['subtypes_filter'] = $subtypes_filter;
		}

	}

	$user_river_options[$tab][$column]['type'] = $type;
	$user_river_options[$tab][$column]['network'] = 'elgg';

} else if ($submit == 'twitter') {
	$twitter_type = get_input('twitter-type');
	$search = get_input('twitter-search');
	$twitter_account = (int) get_input('twitter-account', false);

	$user_river_options[$tab][$column]['subtitle'] = get_entity($twitter_account)->screen_name;

	switch ($twitter_type) {
		case 'get_searchTweets':
			$user_river_options[$tab][$column]['title'] = $search;
			$user_river_options[$tab][$column]['subtitle'] = 'deck_river:twitter:feed:search';
			$user_river_options[$tab][$column]['search'] = $search;
			//$user_river_options[$tab][$column]['direct = 'https://search.twitter.com/search.json?q=' . urlencode($search) . '&rpp=100&include_entities=1';
			break;
		case 'get_searchTweets-popular':
			$user_river_options[$tab][$column]['title'] = $search;
			$user_river_options[$tab][$column]['subtitle'] = 'deck_river:twitter:feed:search';
			$user_river_options[$tab][$column]['search'] = $search;
			//$user_river_options[$tab][$column]['direct = 'https://search.twitter.com/search.json?q=' . urlencode($search) . '&rpp=100&include_entities=1&result_type=popular';
			break;
		case 'get_statusesHome_timeline':
			$user_river_options[$tab][$column]['title'] = 'deck_river:twitter:feed:home';
			break;
		case 'get_statusesMentions_timeline':
			$user_river_options[$tab][$column]['title'] = '@'.get_entity($twitter_account)->screen_name;
			$user_river_options[$tab][$column]['subtitle'] = 'river:mentions';
			break;
		case 'get_statusesUser_timeline':
			$user_river_options[$tab][$column]['title'] = 'deck_river:twitter:feed:user';
			break;
		case 'get_listsStatuses':
			$user_river_options[$tab][$column]['title'] = get_input('twitter_list_name');
			$user_river_options[$tab][$column]['subtitle'] = 'deck_river:twitter:list';
			$user_river_options[$tab][$column]['list_id'] = (int) get_input('twitter-lists', false);
			$user_river_options[$tab][$column]['list_name'] = get_input('twitter_list_name');
			break;
		case 'get_direct_messages':
			$user_river_options[$tab][$column]['title'] = 'deck_river:twitter:feed:dm:recept';
			break;
		case 'get_direct_messagesSent':
			$user_river_options[$tab][$column]['title'] = 'deck_river:twitter:feed:dm:sent';
			break;
		case 'get_favoritesList':
			$user_river_options[$tab][$column]['title'] = 'deck_river:twitter:feed:favorites';
			break;

		/*case 'twitter:users/search':
			$user_river_options[$tab][$column]['title'] = 'users/search';
			$user_river_options[$tab][$column]['subtitle'] = 'manutopik';
			$return['network']'] = true;
			$user_river_options[$tab][$column]['network'] = $return['network']'];
			break;*/
		default:
			break;
	}

	$user_river_options[$tab][$column]['account'] = $twitter_account;
	$user_river_options[$tab][$column]['type'] = $twitter_type;
	$user_river_options[$tab][$column]['network'] = 'twitter';

} else if ($submit == 'facebook') {
	$facebook_type = get_input('facebook-type');
	$search = get_input('facebook-search');
	$facebook_account_guid = (int) get_input('facebook-account', false);
	$facebook_account = get_entity($facebook_account_guid);

	$user_river_options[$tab][$column] = array(
		'account' => $facebook_account_guid,
		'type' => $facebook_type,
		'token' => $facebook_account->oauth_token,
		'query' => $facebook_account->user_id . '/' . $facebook_type,
		'network' => 'facebook',
		'username' => $facebook_account->username,
		'user_id' => $facebook_account->icon ? $facebook_account->parent_id : $facebook_account->user_id,
		'subtitle' => $facebook_account->name,
		//'fields' => 'caption,created_time,from,link,message,story,story_tags,id,full_picture,icon,name,object_id,parent_id,type,with_tags,description,shares,via,feed_targeting,to,source,properties,subscribed,updated_time,picture,is_published,privacy,status_type,targeting,timeline_visibility,comments.fields(parent,id,like_count,message,created_time,from,attachment,can_comment,can_remove,comment_count,message_tags,user_likes),likes.fields(username)',
	);

	switch ($facebook_type) {
		case 'home':
			$user_river_options[$tab][$column]['title'] = 'deck_river:facebook:feed:home';
			break;
		case 'home_fql':
			$user_river_options[$tab][$column]['title'] = 'deck_river:facebook:feed:home_fql';
			$user_river_options[$tab][$column]['type'] = 'stream';
			$user_river_options[$tab][$column]['query'] = "filter_key='others'";
			break;
		case 'feed':
			if ($facebook_account->icon) { // this is a group
				$user_river_options[$tab][$column]['title'] = array('deck_river:facebook:feed:group_feed', $facebook_account->name);
				$user_river_options[$tab][$column]['subtitle'] = array('deck_river:account', $facebook_account->username);
			} else if ($facebook_account->parent_id) { // this is a page
				$user_river_options[$tab][$column]['title'] = array('deck_river:facebook:feed:page_feed', $facebook_account->name);
				$user_river_options[$tab][$column]['subtitle'] = array('deck_river:account', $facebook_account->name);
				$user_river_options[$tab][$column]['username'] = $facebook_account->name;
			} else { // this is a facebook user
				$user_river_options[$tab][$column]['title'] = 'deck_river:facebook:feed:feed';
			}
			break;
		case 'statuses':
			$user_river_options[$tab][$column]['title'] = 'deck_river:facebook:feed:statuses';
			$user_river_options[$tab][$column]['fields'] = '';
			break;
		case 'links':
			$user_river_options[$tab][$column]['title'] = 'deck_river:facebook:feed:links';
			$user_river_options[$tab][$column]['fields'] = 'caption,created_time,from,link,message,id,icon,name,description,via,picture,privacy,comments.fields(parent,id,like_count,message,created_time,from,attachment,can_comment,can_remove,comment_count,message_tags,user_likes),likes.fields(username)';
			break;
		case 'page':
			$user_river_options[$tab][$column]['title'] = array('deck_river:facebook:feed:page_feed', get_input('facebook-page_name'));
			$user_river_options[$tab][$column]['subtitle'] = array('deck_river:account', $facebook_account->name);
			$user_river_options[$tab][$column]['username'] = $facebook_account->name;
			$user_river_options[$tab][$column]['query'] = get_input('facebook-page_id'). '/feed';
			$user_river_options[$tab][$column]['page_name'] = get_input('facebook-page_name');
			$user_river_options[$tab][$column]['page_id'] = get_input('facebook-page_id');
			break;
		case 'search':
			$user_river_options[$tab][$column]['title'] = 'deck_river:facebook:feed:search';
			$user_river_options[$tab][$column]['subtitle'] = '"' . $search . '" - ' . $facebook_account->name;
			$user_river_options[$tab][$column]['search'] = $search;
			$user_river_options[$tab][$column]['query'] = 'search?type=post&q=' . $search;
			$user_river_options[$tab][$column]['fields'] = '';
			break;
		default:
			break;
	}

}

$return['deck_river_settings'] = $user_river_options;
set_private_setting($owner, 'deck_river_settings', json_encode($return['deck_river_settings']));

$return['column'] = $column;
$return['header'] = elgg_view('page/layouts/content/deck_river_column_header', array('column_settings' => $user_river_options[$tab][$column]));

echo json_encode($return);
