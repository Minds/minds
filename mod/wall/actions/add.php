<?php
/**
 * Action for adding a wire post
 * 
 */

// don't filter since we strip and filter escapes some characters
$body = get_input('body', '', false);

$method = 'site';
$to_guid = get_input('to_guid', elgg_get_logged_in_user_guid());
$from_guid = elgg_get_logged_in_user_guid();
$access_id = get_default_access(); //hard coded as we seem to be getting errors with ACCESS_DEFAULT
$message = get_input('body');
$ref = get_input('ref', 'wall');

// get social permissions
$facebook = get_input('facebook');
$twitter =  get_input('twitter');

// make sure the post isn't blank
if (empty($body)) {
	register_error(elgg_echo("wall:blank"));
	forward(REFERER);
}

$group = get_entity($to_guid, 'group');
if($group instanceof ElggGroup){
	$access_id = $group->group_acl;
	$container_guid = $to_guid;
}

//elgg_set_context('wall_post');

$post = new WallPost;
$post->to_guid = $to_guid;
$post->container_guid = $container_guid;
$post->owner_guid = $from_guid;
$post->access_id = $access_id;
$post->message = $message;
$post->method = $method;
$post->facebook = $facebook;
$post->twitter = $twitter;

$guid = $post->save();
if (!$guid) {
	register_error(elgg_echo("wall:error"));
	//forward(REFERER);
} else {

//add the message
//$news_id = add_to_river('river/object/wall/create', 'create', $from_guid, $guid);

/**
 * attachement
 */
$attachment = new PostAttachment();
$attachment->container_guid = $to_guid;
if (isset($_FILES['attachment']['name']) && !empty($_FILES['attachment']['name'])) {
			
	$mime_type = $attachment->detectMimeType($_FILES['attachment']['tmp_name'], $_FILES['attachment']['type']);
	$attachment->setMimeType($mime_type);
	$attachment->simpletype = file_get_simple_type($mime_type);
		
	$attachment->save($_FILES);
}

$river = new ElggRiverItem(array(
	'to_guid' => $to_guid,
	'subject_guid' => elgg_get_logged_in_user_guid(),
	'body' => $message,
	'view' => 'river/object/wall/create',
	'object_guid' => $post->guid,
	'attachment_guid' => $attachment->guid
	));

$river->save();

$output = '<li class="elgg-item">' . elgg_view_list_item($river, array('list_class'=>'elgg-list elgg-list-river elgg-river', 'class'=>'elgg-item elgg-river-item')) . '</li>';


//notification_create(array($to_guid), $from_guid, $guid, array('description'=>$message,'notification_view'=>'wall'));

echo $output;

//detect @ command and if present check username
preg_match_all("/@[a-zA-Z0-9_]+/", $message, $matches);

foreach($matches as $value){
	foreach($value as $value){
		$username = str_replace('@', '', $value);
		$mentioned = get_user_by_username($username);
		if($mentioned){
			//notification_create(array($mentioned->guid), $from_guid, $guid, array('description'=>$message,'notification_view'=>'mention'));
			\elgg_trigger_plugin_hook('notification', 'all', array(
				'to' => array($mentioned->guid),
				'object_guid'=>$from_guid,
				'description'=>$message,
				'notification_view'=>'mention'
			));
		}
	}
}

system_message(elgg_echo("wall:posted"));
}
