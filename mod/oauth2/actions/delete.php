<?php
/**
 * Delete blog entity
 *
 * @package Blog
 */

$guid = get_input('guid');
$entity = get_entity($guid,'object');

if (elgg_instanceof($entity, 'object', 'oauth2_client') && $entity->canEdit()) {

	if ($entity->delete()) {
		system_message(elgg_echo('oauth2:application:deleted'));
	} else {
		register_error(elgg_echo('oauth2:application:cannot_delete'));
	}

} else {
	register_error(elgg_echo('oauth2:application:not_found'));
}

forward(REFERER);
