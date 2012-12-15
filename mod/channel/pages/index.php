<?php
/**
 * Channels index
 *
 */

elgg_load_library('channels:suggested');

$page_owner = elgg_get_logged_in_user_entity();
elgg_set_page_owner_guid($page_owner->guid);

$friends = get_input('friends', 10);
$groups = get_input('groups', 10);

$num_members = get_number_users();

$title = elgg_echo('channels');

$options = array('type' => 'user', 'full_view' => false);
switch ($vars['page']) {
	case 'subscribers':
		$content = elgg_view_entity_list($page_owner->getFriendsOf());
		break;
	case 'subscriptions':
		$content = elgg_view_entity_list($page_owner->getFriends());
		break;
	case 'popular':
		$options['relationship'] = 'friend';
		$options['inverse_relationship'] = false;
		$content = elgg_list_entities_from_relationship_count($options);
		break;
	case 'suggested':
		$people = suggested_friends_get_people($page_owner->guid, $friends, $groups);
		$content = elgg_view('suggested_friends/list', array('people' => $people));
		break;
	case 'online':
		$content = get_online_users();
		break;
	case 'newest':
	default:
		$content = elgg_list_entities($options);
		break;
}

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('channels/sidebar'),
	'title' => $title,
	'filter_override' => elgg_view('channels/nav', array('selected' => $vars['page'])),
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
