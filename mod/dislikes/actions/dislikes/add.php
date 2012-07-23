<?php
/**
 * Elgg add dislike action
 *
 */

$entity_guid = (int) get_input('guid');

//check to see if the user has already disliked the item
if (elgg_annotation_exists($entity_guid, 'dislikes')) {
	system_message(elgg_echo("dislikes:alreadydisliked"));
	forward(REFERER);
}
// Let's see if we can get an entity with the specified GUID
$entity = get_entity($entity_guid);
if (!$entity) {
	register_error(elgg_echo("dislikes:notfound"));
	forward(REFERER);
}

// limit dislikes through a plugin hook (to prevent disliking your own content for example)
if (!$entity->canAnnotate(0, 'dislikes')) {
	// plugins should register the error message to explain why disliking isn't allowed
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();
$annotation = create_annotation($entity->guid,
								'dislikes',
								"dislikes",
								"",
								$user->guid,
								$entity->access_id);

// tell user annotation didn't work if that is the case
if (!$annotation) {
	register_error(elgg_echo("dislikes:failure"));
	forward(REFERER);
}

// notify if poster wasn't owner
if ($entity->owner_guid != $user->guid) {

	dislikes_notify_user($entity->getOwnerEntity(), $user, $entity);
}

system_message(elgg_echo("dislikes:dislikes"));

// Forward back to the page where the user 'disliked' the object
forward(REFERER);
