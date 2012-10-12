<?php
/**
 * Action for adding a wire post
 * 
 */

// don't filter since we strip and filter escapes some characters
$body = get_input('body', '', false);

$method = 'site';
$to_guid = get_input('to_guid');
$from_guid = elgg_get_logged_in_user_guid();
$access_id = ACCESS_DEFAULT;
$message = get_input('body');

// make sure the post isn't blank
if (empty($body)) {
	register_error(elgg_echo("wall:blank"));
	forward(REFERER);
}

$to = get_entity($to_guid);
if($to instanceof ElggGroup){
	$access_id = $to->group_acl;
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

$guid = $post->save();
if (!$guid) {
	register_error(elgg_echo("wall:error"));
	//forward(REFERER);
} else {

$post = get_entity($guid);

$id = "elgg-{$post->getType()}-{$post->guid}";
$time = $post->time_created;
$output = "<li id=\"$id\" class=\"elgg-item\" data-timestamp=\"$time\">";
$output .= elgg_view_list_item($post);
$output .= '</li>';

echo $output;

//add the message
add_to_river('river/object/wall/create', 'create', $from_guid, $guid);

notification_create(array($to_guid), $from_guid, $guid, array('description'=>$message,'notification_view'=>'wall'));

system_message(elgg_echo("wall:posted"));

}
