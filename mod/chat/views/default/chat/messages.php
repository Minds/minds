<?php

$chat = elgg_extract('entity', $vars);

/**
 * Get messages ascending to get latest messages and then reverse
 * them to make the order chronological (latest messages at bottom).
 */
$messages = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'chat_message', 
	'container_guid' => $chat->getGUID(),
	'limit' => 6,
	'order_by' => 'e.time_created desc',
	'pagination' => false,
));
$messages = array_reverse($messages);

$message_list = elgg_view_entity_list($messages);

$body_vars = chat_prepare_message_form_vars();
$body_vars['container_guid'] = $chat->getGUID();
$form_vars = array();

$message_form = elgg_view_form('chat/message/save', $form_vars, $body_vars);

$more_link = elgg_view('output/url', array(
	'name' => 'chat-view-more',
	'id' => 'chat-view-more',
	'text' => elgg_echo('chat:more'),
	'href' => '',
));

echo <<<MSG
<div class="elgg-chat mtl">
	$more_link
	<div class="elgg-chat-messages pbm">
		$message_list
	</div>
	$message_form
</div>
MSG;
