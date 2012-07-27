<?php

$container_guid = get_input('container_guid', null);
$container = get_entity($container_guid);
$river_id = get_input('river_id', null);

if (!$river_id && !elgg_instanceof($container)) {
    register_error(elgg_echo('hj:likes:cantfind'));
    return true;
}

if (get_input('action_type') == 'like') {
    $annotation_value = '1';
} else {
    $annotation_value = '0';
    $params = array(
        'container_guid' => $container_guid,
        'river_id' => $river_id
    );
    $likes = hj_alive_does_user_like($params);
    $likes = $likes['likes'][0];
    $guid = $likes->guid;
}

if (!get_input('access_id', false)) {
    if (!$access_id = $container->access_id) {
        if (!$river_id) {
            $access_id = ACCESS_DEFAULT;
        } else {
            $river_item = elgg_get_river(array('id' => $river_id));
            $river_item = $river_item[0];
            $access_id = ACCESS_LOGGED_IN;
        }
    }
}

if (!$guid) {
    $annotation = new hjAnnotation($guid);
    $annotation->annotation_value = $annotation_value;
    $annotation->annotation_name = 'likes';
    $annotation->owner_guid = elgg_get_logged_in_user_guid();
    $annotation->container_guid = $container_guid;
    $annotation->river_id = $river_id;
    $annotation->access_id = $access_id;
    $guid = $annotation->save();

    if ($guid) {
        system_message(elgg_echo('hj:likes:savesuccess'));
    } else {
        register_error(elgg_echo('hj:likes:saveerror'));
    }
} else {
    $annotation = get_entity($guid);
    if ($annotation->delete()) {
        system_message(elgg_echo('hj:likes:likeremoved'));
    }
}


return true;