<?php
/**
 * Elgg videochat plugin friends page
 *
 */

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	forward('videochat/all');
}

elgg_push_breadcrumb($page_owner->name, "videochat/owner/$page_owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$title = elgg_echo('videochat:friends');

$content = list_user_friends_objects($page_owner->guid, 'videochat', 10, false);
if (!$content) {
	$content = elgg_echo('videochat:none');
}

$sidebar = elgg_view('videochat/sidebar');
$params = array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
