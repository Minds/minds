<?php


$guid = get_input('guid');
$entity = get_entity($guid, 'object');
if(!$entity && get_input('video_id')){
	$entity = new ElggObject();
	$entity->subtype = 'kaltura_video';
	$entity->kaltura_video_id = get_input('video_id');
}

$entity->title = get_input('title');
$entity->description = get_input('description');
$entity->license = get_input('license');
$entity->access_id = get_input('access_id');
$entity->tags = get_input('tags');

if(get_input('thumbnailData')){
	$thumb = str_replace('data:image/jpeg;base64,', '', get_input('thumbnailData'));
	$thumb = str_replace(' ', '+', $thumb);
	$data = base64_decode($thumb);
	
	$file = new ElggFile();
	$file->owner_guid = $entity->getOwnerEntity()->guid;
	$file->setFilename("archive/thumbnails/{$entity->guid}.jpg");
	$file->open('write');
	$file->write($data);
	$file->close();
	$entity->thumbnail = get_input('thumbSec');
}

if (empty($entity->title)) {
	register_error(elgg_echo("album:blank"));
	forward(REFERER);
}

if($entity->license == 'not-selected'){
	register_error(elgg_echo('minds:license:not-selected'));
	forward(REFERER);
}


$entity->save();
forward($entity->getURL());
