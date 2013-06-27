<?php

gatekeeper();

$url=get_input('url');
$vote = get_input('vote');


// See if we have an entity for this
elgg_set_ignore_access();
$entity = elgg_get_entities_from_metadata(array(
    'type' => 'object',
    'subtype' => 'mind_widget_voting_stub',
    'limit' => 1,
    'metadata_name' => 'url',
    'metadata_value' => $url,
));
if (!$entity){
    $entity = new ElggObject();
    $entity->subtype = 'mind_widget_voting_stub';
    $entity->access_id = ACCESS_PRIVATE;
    $entity->owner_guid = 0;
    
    $entity->save();
    $entity->url = $url;
} else {
    $entity = $entity[0];
}

$entity_guid = $entity->guid;

// We're running into access problems on the centralised object, so we're going to clone the thumbs functionality here
if ($vote == 'up')
{
    if (elgg_annotation_exists($entity_guid, 'thumbs:up')) {
        $options = array('annotation_names' => array('thumbs:up'), 'annotation_owner_guids' => array(elgg_get_logged_in_user_guid()));
        $delete = elgg_delete_annotations($options);

        $entity -> thumbcount--;
    } else {

        if (elgg_annotation_exists($entity_guid, 'thumbs:down')) {
                $options = array('annotation_names' => array('thumbs:down'), 'annotation_owner_guids' => array(elgg_get_logged_in_user_guid()));
                elgg_delete_annotations($options);

        }
        

        $entity -> thumbcount++;

        $annotation = create_annotation($entity -> guid, 'thumbs:up', 1, "", elgg_get_logged_in_user_guid(), $entity -> access_id);
        $entity -> save();


        notification_create(array($entity -> getOwnerGUID()), elgg_get_logged_in_user_guid(), $entity -> guid, array('notification_view' => 'like'));
    }        
}

if ($vote == 'down') {
    
    if (elgg_annotation_exists($entity_guid, 'thumbs:down')) {
            $options = array('annotation_names' => array('thumbs:down'), 'annotation_owner_guids' => array(elgg_get_logged_in_user_guid()));
            $delete = elgg_delete_annotations($options);

            $entity -> thumbcount++;
    } else {

            if (elgg_annotation_exists($entity_guid, 'thumbs:up')) {
                    $options = array('annotation_names' => array('thumbs:up'), 'annotation_owner_guids' => array(elgg_get_logged_in_user_guid()));
                    elgg_delete_annotations($options);

            }

            // limit likes through a plugin hook (to prevent liking your own content for example)
            if (!$entity -> canAnnotate(0, 'thumbs:up')) {
                    // plugins should register the error message to explain why liking isn't allowed
                    forward(REFERER);
            }

            $entity -> thumbcount--;

            $annotation = create_annotation($entity -> guid, 'thumbs:down', 1, "", elgg_get_logged_in_user_guid(), $entity -> access_id);
            $entity -> save();
    }
}