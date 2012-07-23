<?php
/**
 * Ajax endpoint for getting new chat messages.
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

$user = elgg_get_logged_in_user_entity();

$time_created = get_input('time_created');
$guid = get_input('guid');

$chat = get_entity($guid);
if (!elgg_instanceof($chat, 'object', 'chat') || !$chat->isMember()) {
	echo false;
	exit;
}

$options = array(
	'type' => 'object',
	'subtype' => 'chat_message',
	'container_guid' => $guid,
	'order_by' => 'e.time_created asc',
);

$pagination = get_input('pagination', false);
if ($pagination) {
	// Get old messages
	$options['wheres'] = array("time_created < $time_created");
	$options['limit'] = 6;
} else {
	// Get the newest messages
	$options['wheres'] = array("time_created > $time_created");
	$options['limit'] = false;
}

$messages = elgg_get_entities($options);

$html = '';
if ($messages) {
	elgg_load_library('chat');

	foreach ($messages as $message) {
		$id = "elgg-object-{$message->getGUID()}";
		$item = elgg_view_list_item($message);
		$html .= "<li id=\"$id\" class=\"elgg-item\">$item</li>";
	}
	
	$chat->resetUnreadMessageCount();
}

echo $html;
