<?php

if (!get_input('comment')) {
    register_error(elgg_echo('hj:alive:comments:valuecantbeblank'));
    return true;
}

$ia = elgg_set_ignore_access();

$type = get_input('type', null);
$pid = get_input('pid', null);
$comment = urldecode(get_input('comment', null));

if (!elgg_is_logged_in()){
	
	//relies on the minds user account being created @todo fix this?
	$owner = get_user_by_username('minds');
	$owner = new stdClass();
	$owner->guid = 0;

	if (false !== strpos($comment, 'http')){
		exit; //most probably spam
	}

}else {
	$owner = elgg_get_logged_in_user_entity();
}

$mc = new MindsComments();
$create = $mc->create($type, $pid, $comment);

if($create['ok'] == true){
	
	/*
	 * Purge the comments cache
	 */
	$es = new elasticsearch();
	$es->purgeCache('comments.'.$type.'.'.$pid);
	
	system_message(elgg_echo('minds_comments:save:success'));
	
	$data['_id'] = time().$owner->guid;
	$data['_type'] = $type;
	$data['_source']['pid'] = $pid;
	$data['_source']['owner_guid'] = $owner->guid;
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
//elgg_set_plugin_user_setting('commented', true, elgg_get_logged_in_user_guid(), 'minds_comments'); // Do we actually need this still?

$entity = get_entity($pid, 'object');

\elgg_trigger_plugin_hook('notification', 'all', array(
	'to' => array($entity->owner_guid),
	'object_guid'=>$entity->guid,
	'description'=>$desc,
	'notification_view'=>'comment'
));


elgg_trigger_event('comment:create', 'comment', $data); 

elgg_set_ignore_access($ia);
exit;
