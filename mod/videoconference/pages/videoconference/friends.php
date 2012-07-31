<?php
/**
 * Elgg videoconference plugin friends page
 *
 */

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	forward('videoconference/all');
}

elgg_push_breadcrumb($page_owner->name, "videoconference/owner/$page_owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$title = elgg_echo('videoconference:friends');

$content = list_user_friends_objects($page_owner->guid, 'videoconference', 10, false);
if (!$content) {
	$content = elgg_echo('videoconference:none');
}

$sidebar = elgg_view('videoconference/sidebar');
$params = array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
