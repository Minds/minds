<?php
/**
 * Create an empty popup module to be populated via AJAX.
 */

 elgg_push_context('chat_preview');
 
$add_chat_link = elgg_view('output/url', array(
	'href' => 'chat/add',
	'text' => elgg_echo('chat:add'),
	'class' => 'float-alt',
));

$vars = array(
	'class' => 'hidden elgg-chat-messages-preview hz-list',
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

$chats = elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'subtype' => 'chat',
			'relationship' => 'member',
			'relationship_guid' => elgg_get_logged_in_user_guid(),
			'inverse_relationship' => false,
			'limit' => $limit,
		));
		
$preview = elgg_view_entity_list($chats, array('full_view' => false));

if ($messages) {
	elgg_load_library('chat');

	foreach ($messages as $message) {
		$id = "elgg-object-{$message->getGUID()}";
		$item = elgg_view_list_item($message);
		$html .= "<li id=\"$id\" class=\"elgg-item\">$item</li>";
	}
	
	$chat->resetUnreadMessageCount();
}

$body = <<<HTML
	$preview
	$all_chats_link
	<span id="chat-messages-none" class="hidden">$none_message</span>
	$add_chat_link
HTML;

$content = elgg_view_module('popup', $title, $body, $vars);

echo $content;

elgg_pop_context();
