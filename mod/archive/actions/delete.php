<?php
gatekeeper();

$guid = get_input('guid');
$entity = get_entity($guid, 'object');

if(!$entity) //Elgg entity doesn't exists we return
{
    system_message(elgg_echo('minds:archive:delete:success'));
    forward('archive/all');
}

if($entity->getSubtype() == 'video'){
	$entity->delete();
}elseif($entity->getSubtype() == 'kaltura_video'){
	elgg_load_library('archive:kaltura');
	try{
		$kmodel = KalturaModel::getInstance();
		$entry = $kmodel->getEntry($entity->kaltura_video_id);
		$kmodel->deleteEntry($entity->kaltura_video_id);
	
	} catch(Exception $e){
	}
	$entity->delete();
	forward('archive/all');
} elseif($entity->getSubtype() == 'file') {

	$thumbnails = array($entity->thumbnail, $entity->smallthumb, $entity->largethumb);
		foreach ($thumbnails as $thumbnail) {
			if ($thumbnail) {
				$delfile = new ElggFile();
				$delfile->owner_guid = $entity->owner_guid;
				$delfile->setFilename($thumbnail);
				$delfile->delete();
			}
		}
	if($entity->delete()){
		success_message(elgg_echo('minds:archive:delete:success'));
		forward('archive/all');
	} else {
		register_error(elgg_echo('minds:archive:delete:error'));
	}
} elseif($entity->getSubtype() == 'image' || $entity->getSubtype() == 'album'){
	if($entity->delete()){
		system_message(elgg_echo('minds:archive:delete:success'));
		forward('archive/'.$entity->getOwnerEntity()->username);
	} else {
		register_error(elgg_echo('minds:archive:delete:error'));
	}
}
