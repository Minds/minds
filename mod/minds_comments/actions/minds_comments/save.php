<?php

if (!get_input('comment')) {
    register_error(elgg_echo('hj:alive:comments:valuecantbeblank'));
    return true;
}

if (!elgg_is_logged_in()){
	register_error(elgg_echo('minds_comment:mustbeloggedin'));
	return true;
}

$type = get_input('type', null);
$pid = get_input('pid', null);
$comment = urldecode(get_input('comment', null));

$mc = new MindsComments();
$create = $mc->create($type, $pid, $comment);

if($create['ok'] == true){
	
	/*
	 * Purge the comments cache
	 */
	$es = new elasticsearch();
	$es->purgeCache('comments.'.$type.'.'.$pid);
	
	system_message(elgg_echo('minds_comments:save:success'));
	
	$data['_id'] = time().elgg_get_logged_in_user_guid();
	$data['_type'] = $type;
	$data['_source']['pid'] = $pid;
	$data['_source']['owner_guid'] = elgg_get_logged_in_user_guid();
	$data['_source']['description'] = $comment;
	$data['_source']['time_created'] = time();
	//header('Content-Type: application/json');
	$output = minds_comments_view_comment($data);
//	minds_comments_notification($type, $pid, $comment);
	if(get_input('redirect_url')){
		forward(get_input('redirect_url'));
		return true;
	}
	print(json_encode($output));
} else {
	 register_error(elgg_echo('minds_comments:save:error'));
}
//user setting for orientation
elgg_set_plugin_user_setting('commented', true, elgg_get_logged_in_user_guid(), 'minds_comments');

/*//get a list of all the users who have previously commented
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
*/
$entity = get_entity($pid, 'object');
//$owner = get_entity($entity->ower_guid);
//$items = elgg_get_entities_from_metadata($options);
/*foreach($items as $item){
	
	$to_guids[] = $item->owner_guid;
	
}
	$to_guids[] = $subject_guid;
	$to = array_unique($to_guids);
*/
notification_create(array($entity->owner_guid), elgg_get_logged_in_user_guid(), $pid, array('description'=>get_input('annotation_value', ''), 'notification_view'=>'comment'));
exit;
