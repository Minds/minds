<?php
/**
 * Elgg webinar delete
 *
 * @package Elgg.webinar
 */

$guid =  get_input('guid');

$entity = get_entity($guid, 'object');

if (elgg_instanceof($container, 'object', 'webinar')) {
	register_error(elgg_echo("webinar:delete:failed"));
	forward(REFERER);
}

if (!$entity->canEdit()) {
	register_error(elgg_echo("webinar:delete:failed"));
	forward($entity->getURL());
}

$container = $entity->getContainerEntity();

if (!$entity->delete()) {
	register_error(elgg_echo("webinar:delete:failed"));
} else {
	system_message(elgg_echo("webinar:delete:success"));
}

if (elgg_instanceof($container, 'group')) {
	forward("webinar/group/$container->guid/all");
} else {
	forward("webinar/owner/$container->username");
}
