<?php
/**
 * Minds Inviter Index
 *
 */
elgg_load_library('orientation');

$title = elgg_echo('orientation:title');

$content = elgg_view('orientation/index');

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('orientation/sidebar'),
	'title' => $title,
	'class' => 'orientation'
);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page($title, $body);
