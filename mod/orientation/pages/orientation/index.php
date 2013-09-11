<?php
/**
 * Minds Inviter Index
 *
 */
elgg_load_library('orientation');

gatekeeper();

$title = elgg_echo('orientation:title');

$content = elgg_view('orientation/index');

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('orientation/sidebar'),
	'title' => $title,
	'class' => 'orientation'
);

$body = elgg_view_layout('tiles', $params);

echo elgg_view_page($title, $body);
