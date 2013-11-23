<?php
/**
 * Minds Inviter Index
 *
 */

$title = elgg_echo('minds_inviter:title');

$content = elgg_view('minds_inviter/index');

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('minds_inviter/sidebar'),
	'title' => $title,
	'filter' => ''
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
