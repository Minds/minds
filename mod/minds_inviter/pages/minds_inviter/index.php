<?php
/**
 * Minds Inviter Index
 *
 */

$title = '';

$content = elgg_view('minds_inviter/index');

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('minds_inviter/sidebar'),
	'title' => $title . " ($num_members)",
);

$body = elgg_view_layout('one_column', $params);

echo elgg_view_page($title, $body);
