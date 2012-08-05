<?php

if (!get_input('annotation_value')) {
    register_error(elgg_echo('hj:alive:comments:valuecantbeblank'));
    return true;
}

$parent_guid = get_input('parent_guid', null);
$parent = get_entity($parent_guid);
$river_id = get_input('river_id', false);

$object_guid = get_input('object_guid', false);
$subject_guid = get_input('subject_guid', false);

if (!$river_id && !elgg_instanceof($parent)) {
    register_error(elgg_echo('hj:comments:cantfind'));
    return true;
}

$annotation = new hjAnnotation();
$annotation->annotation_value = get_input('annotation_value', '');
$annotation->annotation_name = get_input('aname', 'generic_comment');
$annotation->title = get_input('title', '');
$annotation->owner_guid = elgg_get_logged_in_user_guid();
//set it as metadata and then there is no problem
$annotation->parent_guid = $parent_guid;
$annotation->river_id = $river_id;
$annotation->access_id = get_input('access_id', ACCESS_DEFAULT);
$guid = $annotation->save();


//get a list of all the users who have previously commented
$options = array(
        'type' => 'object',
        'subtype' => 'hjannotation',
        //'owner_guid' => $user->guid,
        //'container_guid' => $container_guid,
		'metadata_name_value_pairs' => array(
            array('name' => 'parent_guid', 'value' => $parent_guid)
        ),
        'limit' => 0,
    );
$items = elgg_get_entities_from_metadata($options);
foreach($items as $item){
	
	$to_guids[] = $item->owner_guid;
	
}
	$to_guids[] = $subject_guid;
	$to = array_unique($to_guids);

notification_create($to, elgg_get_logged_in_user_guid(), $parent_guid, array('description'=>get_input('annotation_value', ''), 'notification_view'=>'comment'));

if ($guid) {
    system_message(elgg_echo('hj:comments:savesuccess'));
} else {
    register_error(elgg_echo('hj:comments:saveerror'));
}

header('Content-Type: application/json');
print(json_encode($guid));