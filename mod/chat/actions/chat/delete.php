<?php
/**
 * Delete chat entity
 *
 * @package Chat
 */

$guid = get_input('guid');
$chat = get_entity($guid);

if (elgg_instanceof($chat, 'object', 'chat') && $chat->canEdit()) {
	if ($chat->delete()) {
		system_message(elgg_echo('chat:message:deleted'));
		forward("chat/all");
	} else {
		register_error(elgg_echo('chat:error:cannot_delete'));
	}
} else {
	register_error(elgg_echo('noaccess'));
}

forward(REFERER);