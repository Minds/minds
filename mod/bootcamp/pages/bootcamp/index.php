<?php
/**
 * Minds Inviter Index
 *
 */
elgg_load_library('bootcamp');

$title = elgg_echo('bootcamp:title');

$content = elgg_view('bootcamp/index');

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('bootcamp/sidebar'),
	'title' => $title,
	'class' => 'bootcamp'
);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page($title, $body);
