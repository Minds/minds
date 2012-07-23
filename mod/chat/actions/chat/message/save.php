<?php
/**
 * Save chat message entity
 *
 * @package Chat
 */

$guid = get_input('guid');
$message = get_input('message');
$container_guid = get_input('container_guid');

elgg_make_sticky_form('chat_message');

if (empty($message)) {
	register_error(elgg_echo("chat:error:missing:message"));
	forward(REFERER);
}

if (empty($container_guid)) {
	register_error(elgg_echo("chat:error:missing:container_guid"));
	forward(REFERER);
}

elgg_push_context('chat_message');

$user = elgg_get_logged_in_user_entity();

if ($guid) {
	$entity = get_entity($guid);
	
	if (!elgg_instanceof($entity, 'object', 'chat_message') && $entity->canEdit()) {
		register_error(elgg_echo('noaccess'));
		forward(REFERER);
	}
} else {
	$entity = new ElggObject();
	$entity->subtype = 'chat_message';
	$entity->access_id = ACCESS_LOGGED_IN;
	$entity->container_guid = $container_guid;
}

$entity->description = $message;

if ($entity->save()) {
	elgg_clear_sticky_form('chat_message');
	
	$chat = $entity->getContainerEntity();
	
	$members = $chat->getMemberEntities();
	foreach ($members as $member) {
		// No unread annotations for user's own message
		if ($member->getGUID() === $user->getGUID()) {
			continue;
		}
		
		// Mark the message as unread
		$entity->addRelationship($member->getGUID(), 'unread');
		
		// Add number of unread messages also to the chat object
		$chat->increaseUnreadMessageCount($member);
	}
	
	// @todo Should we update the container chat so we can order chats by
	// time_updated? Or is it possible to order by "unread_messages" annotation?
	//$chat->time_updated = time();
} else {
	register_error(elgg_echo('chat:error:cannot_save_message'));
	forward(REFERER);
}

system_message(elgg_echo('chat:message:chat_message:saved'));
forward($entity->getContainerEntity()->getURL());
