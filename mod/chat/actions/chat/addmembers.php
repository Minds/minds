<?php
/**
 * Add chat members
 *
 * @package Chat
 */

$guid = get_input('guid');
$members = get_input('members');

if (empty($members)) {
	register_error(elgg_echo('chat:error:missing:members'));
	forward(REFERER);
}

$entity = get_entity($guid);

if (!elgg_instanceof($entity, 'object', 'chat') && $entity->canEdit()) {
	register_error(elgg_echo('noaccess'));
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();

// Add selected users to the chat
$count = 0;
foreach ($members as $member_guid) {
	// Add relationship "user is a member of this chat".
	if ($entity->addMember($member_guid)) {
		$count++;
	} else {
		$member = get_entity($member_guid);
		register_error(elgg_echo("chat:error:cannot_add_member", array($member->name)));
	}
}

// Allow plural value to be translated separately
if ($count > 1) {
	$message = 'chat:message:members:saved:plurar';
} else {
	$message = 'chat:message:members:saved';
}

system_message(elgg_echo($message, array($count)));
forward($entity->getURL());
