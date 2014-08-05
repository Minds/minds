<?php
/**
 * Show user's scheduled posts
 */

$user = elgg_get_logged_in_user_entity();

$posts = deck_get_scheduled_list($user->guid, $limit = 0, $offset = "");
$content = elgg_view_entity_list($posts);

$params = array(
	'content' => $content,
	'filter_context' => $page_filter,
	'class' => 'deck-scheduler-layout',
	'header' => elgg_view('deck_river/header')
);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page($title, $body, 'default', array('class'=>'deck'));
