<?php
gatekeeper();

$guid = get_input('guid');
$entity = get_entity($guid);

if($entity->getSubtype() == 'kaltura_video'){
	elgg_load_library('archive:kaltura');
	try{
		$kmodel = KalturaModel::getInstance();
		$entry = $kmodel->getEntry($entity->kaltura_video_id);
		$kmodel->deleteEntry($entity->kaltura_video_id);
		$entity>delete();
	} catch(Exception $e){
		register_error('The video was not deleted, please speak to a minds admin');
	}
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
	$entity->delete();
	
} elseif($entity->getSubtype() == 'image' || $entity->getSubtype() == 'album'){
	$entity->delete();
}
