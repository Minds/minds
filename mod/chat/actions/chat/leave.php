<?php
/**
 * Leave chat
 *
 * @package Chat
 */

$guid = get_input('guid');
$chat = get_entity($guid);

$user = elgg_get_logged_in_user_entity();

if (elgg_instanceof($chat, 'object', 'chat') && $chat->isMember()) {
	if ($chat->removeMember($user)) {
		system_message(elgg_echo('chat:message:left'));
		forward("chat/all");
	} else {
		register_error(elgg_echo('chat:error:cannot_leave'));
	}
} else {
	register_error(elgg_echo('noaccess'));
}

forward(REFERER);