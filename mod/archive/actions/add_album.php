<?php

// Get input data
$title = get_input('title');
$description = get_input('description');
$tags = get_input('tags');
$license = get_input('license');
$access_id = get_input('access_id');
$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());
$guid = get_input('guid');

elgg_make_sticky_form('tidypics');

if (empty($title)) {
	register_error(elgg_echo("album:blank"));
	//forward(REFERER);
}


$album = new minds\plugin\archive\entities\album();

$album->owner_guid = elgg_get_logged_in_user_guid();
$album->access_id = $access_id;
$album->title = $title;
$album->description = $description;

if($container_guid != elgg_get_logged_in_user_guid()){
	$album->container_guid = $container_guid;
}

if (!$album->save()) {
	register_error(elgg_echo("album:error"));
	//forward(REFERER);
}

echo $album->guid;
exit;

system_message(elgg_echo("album:created"));
//forward($album->getURL());
