<?php

$guid        = get_input('guid');
$title       = get_input('name');
$description = get_input('url');

if ($guid) {

    $entity = get_entity($guid)

    if (!elgg_instanceof($entity, 'object', 'oauth2_client') || !$entity->canEdit()) {
        register_error(elgg_echo('oauth2:register:app_not_found'));
        forward(REFERRER);
    }

} else {

    $entity = new ElggObject();
    $entity->subtype    = 'oauth2_client';
    $entity->owner_guid = elgg_get_logged_in_user_guid();
    $entity->access_id  = ACCESS_PRIVATE;
}

$entity->title       = $title;
$entity->description = $description;

if (!$entity->save()) {
    register_error(elgg_echo('blog:error:post_not_found'));
    forward(REFERRER);
}

$label = $guid ? 'updated' : 'registered';
system_message(elgg_echo("oauth2:register:$label"));

forward(elgg_get_site_url() . 'oauth2/applications');

