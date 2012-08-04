<?php

if (!get_input('annotation_value')) {
    register_error(elgg_echo('hj:alive:comments:valuecantbeblank'));
    return true;
}

$container_guid = get_input('container_guid', null);
$container = get_entity($container_guid);
$river_id = get_input('river_id', false);

$object_guid = get_input('object_guid', false);
$subject_guid = get_input('subject_guid', false);


if (!$river_id && !elgg_instanceof($container)) {
    register_error(elgg_echo('hj:comments:cantfind'));
    return true;
}

$annotation = new hjAnnotation();
$annotation->annotation_value = get_input('annotation_value', '');
$annotation->annotation_name = get_input('aname', 'generic_comment');
$annotation->title = get_input('title', '');
$annotation->owner_guid = elgg_get_logged_in_user_guid();
$annotation->container_guid = $container_guid;
$annotation->river_id = $river_id;
$annotation->access_id = get_input('access_id', ACCESS_DEFAULT);
$guid = $annotation->save();

//get a list of all the users who have previously commented
$options = array(
        'type' => 'object',
        'subtype' => 'hjannotation',
        //'owner_guid' => $user->guid,
        'metadata_name_value_pairs' => array(
            array('name' => 'river_id', 'value' => $river_id)
        ),
        'limit' => 0,
    );
$items = elgg_get_entities_from_metadata($options);
foreach($items as $item){
	
	$to_guids[] = $item->owner_guid;
	
}
	$to_guids[] = $subject_guid;
	$to = array_unique($to_guids);

notification_create($to, elgg_get_logged_in_user_guid(), $object_guid, array('description'=>get_input('annotation_value', ''), 'notification_view'=>'comment'));

if ($guid) {
    system_message(elgg_echo('hj:comments:savesuccess'));
} else {
    register_error(elgg_echo('hj:comments:saveerror'));
}

header('Content-Type: application/json');
print(json_encode($guid));