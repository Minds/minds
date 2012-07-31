<?php
/**
 * Elgg livestreaming plugin friends page
 *
 */

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	forward('livestreaming/all');
}

elgg_push_breadcrumb($page_owner->name, "livestreaming/owner/$page_owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$title = elgg_echo('livestreaming:friends');

$content = list_user_friends_objects($page_owner->guid, 'livestreaming', 10, false);
if (!$content) {
	$content = elgg_echo('livestreaming:none');
}

$sidebar = elgg_view('livestreaming/sidebar');
$params = array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
