<?php
/**
 * Elgg bookmarks plugin everyone page
 *
 * @package ElggBookmarks
 */

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('bookmarks'));

elgg_register_title_button();

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'bookmarks',
	'limit' => 24,
	'full_view' => false,
	'view_toggle_type' => false
));

if (!$content) {
	$content = elgg_echo('bookmarks:none');
}

$title = elgg_echo('bookmarks:everyone');

$body = elgg_view_layout('gallery', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'class' => 'bookmarks'
));

echo elgg_view_page($title, $body);
