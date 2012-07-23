<?php
/**
 * Delete chat message entity
 *
 * @package Chat
 */

$guid = get_input('guid');
$message = get_entity($guid);

if (elgg_instanceof($message, 'object', 'chat_message') && $message->canEdit()) {
	$container = get_entity($message->container_guid);
	if ($message->delete()) {
		system_message(elgg_echo('chat:message:chat_message:deleted'));
		if (elgg_instanceof($container, 'object', 'chat')) {
			forward($container->getURL());
		}
		forward('chat');
	} else {
		register_error(elgg_echo('chat:error:cannot_delete'));
	}
} else {
	register_error(elgg_echo('noaccess'));
}

forward();