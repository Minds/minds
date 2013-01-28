<?php

$guid        = get_input('guid');
$title       = get_input('name');
$description = get_input('url');

if ($guid) {

    $entity = get_entity($guid)

    if (!elgg_instanceof($entity, 'object', 'oauth2_client') || !$entity->canEdit()) {
        register_error(elgg_echo('blog:error:post_not_found'));
        forward(get_input('forward', REFERER));
    }

} else {

    $entity = new ElggObject();
    $entity->subtype    = 'oauth2_client';
    $entity->owner_guid = $container_guid;
    $entity->access_id  = ACCESS_PRIVATE;

}

$entity->title       = $title;
$entity->description = $description;

if ($entity->save()) {
    
}

