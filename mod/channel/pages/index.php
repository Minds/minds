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

$limit = get_input('limit', 20);
$offset = get_input('offset', '');

$title = elgg_echo('channels');

$options = array('type' => 'user', 'full_view' => false, 'limit'=>$limit);
switch ($vars['page']) {
	case 'subscribers':
		$subscribers = get_user_friends_of($page_owner->guid, '', $limit, $offset);
		$content = elgg_view_entity_list($subscribers,$vars, $offset, $limit, false, false,false);
		break;
	case 'subscriptions':
		$subscriptions = get_user_friends($page_owner->guid, '', $limit, $offset);
                $content = elgg_view_entity_list($subscriptions,$vars, $offset, $limit, false, false,false);
		break;
	case 'popular':
		$options['limit'] = $limit;
		$options['newest_first'] = false;
		$content = elgg_list_entities($options);
		break;
	case 'newest':
                $options['newest_first'] = true;
                $content = elgg_list_entities($options);
		break;
	case 'trending':
	default:
		$guids = analytics_retrieve(array('context'=>'users', 'offset'=>$offset, 'limit'=>$limit));
		$options['guids'] = $guids;
		$content = elgg_list_entities($options);
		break;
}

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('channels/sidebar'),
	'title' => $title,
	'filter_override' => elgg_view('channels/nav', array('selected' => $vars['page'])),
	'class'=> 'channels'
);

$body = elgg_view_layout('tiles', $params);

echo elgg_view_page($title, $body);
