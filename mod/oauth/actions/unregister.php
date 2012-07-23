<?php

  // have to be logged in to get to here at all
gatekeeper();

// copied and adapted from actions/object/delete

$guid = get_input('guid');
	
$entity = get_entity($guid);
	
if (($entity) && ($entity->canEdit()) && $entity->getSubtype() == 'oauthconsumer') {
    if ($entity->delete()) {
	system_message(sprintf(elgg_echo('entity:delete:success'), $guid));
    } else {
	register_error(sprintf(elgg_echo('entity:delete:fail'), $guid));
    }
} else {
    register_error(sprintf(elgg_echo('entity:delete:fail'), $guid));
}
		
forward($_SERVER['HTTP_REFERER']);

?>