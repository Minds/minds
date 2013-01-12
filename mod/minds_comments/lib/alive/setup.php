<?php

function hj_alive_setup() {
    if (elgg_is_logged_in()) {
        //hj_alive_import_annotations();
        elgg_set_plugin_setting('notifications', 'generic_comment, group_topic_post, likes', 'hypeAlive');
        elgg_set_plugin_setting('river_comments', 'on', 'hypeAlive');
        elgg_set_plugin_setting('entity_comments', 'on', 'hypeAlive');
        elgg_set_plugin_setting('forum_comments', 'on', 'hypeAlive');
        elgg_set_plugin_setting('hj:alive:setup', true, 'hypeAlive');
        return true;
    }
    return false;
}

function hj_alive_import_annotations($annotation_name) {

    $annotations = elgg_get_annotations(array(
        'annotation_names' => array($annotation_name)
            ));

    foreach ($annotations as $annotation) {
        if (!hj_alive_annotation_match_exists($annotation)) {
            $import = new hjAnnotation();
            $import->annotation_id = $annotation->id;
            $import->annotation_name = $annotation->name;
            $import->annotation_value = $annotation->value;
            $import->owner_guid = $annotation->owner_guid;
            $import->container_guid = $annotation->entity_guid;
            $import->access_id = $annotation->access_id;
            $import->save(false);
        }
    }

    return true;
}

function hj_alive_annotation_match_exists($annotation) {
    $match = elgg_get_entities_from_metadata(array(
        'type' => 'object',
        'subtype' => 'hjannotation',
        'count' => true,
        'owner_guid' => $annotation->owner_guid,
        'container_guid' => $annotation->entity_guid,
        'metadata_name_value_pairs' => array(
            array('name' => 'annotation_id', 'value' => $annotation->id)
            )));

    if ($match > 0) {
        return true;
    }
    return false;
}