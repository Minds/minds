<?php
/**
 * Create an empty popup module to be populated via AJAX.
 */

$add_chat_link = elgg_view('output/url', array(
	'href' => 'chat/add',
	'text' => elgg_echo('chat:add'),
	'class' => 'float-alt',
));

$vars = array(
	'class' => 'hidden elgg-chat-messages-preview',
	'id' => 'chat-messages-preview',
);

$title = elgg_echo('chat:chats');

// Link to all chats
$all_chats_link = elgg_view('output/url', array(
	'href' => 'chat/all',
	'text' => elgg_echo('chat:view:all'),
	'class' => 'hidden float',
	'id' => 'chat-view-all',
));

$none_message = elgg_echo('chat:none'); 

$body = <<<HTML
	<ul></ul>
	$all_chats_link
	<span id="chat-messages-none" class="hidden">$none_message</span>
	$add_chat_link
HTML;

$content = elgg_view_module('popup', $title, $body, $vars);

echo $content;