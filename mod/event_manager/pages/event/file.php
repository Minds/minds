<?php 

$guid = get_input('guid');
$filename = get_input('file');

if(!empty($guid) && !empty($filename)) {
	if($entity = get_entity($guid)) {
		if($entity->getSubtype() == Event::SUBTYPE) {
			$event = $entity;
		}
	}
	
	$files = json_decode($event->files);
	foreach($files as $file) {
		if(strtolower($file->file) == strtolower($filename)) {
			$prefix = "events/" . $event->getGUID() . "/files/";
				
			$fileHandler = new ElggFile();
			$fileHandler->setFilename($prefix . $file->file);
			
			$fileHandler->owner_guid = $event->owner_guid;
			
			header('Content-Type: '.$file->mime);
			header('Content-Disposition: Attachment; filename=' . $file->file);
			header('Pragma: no-cache');				
			
			echo $fileHandler->grabFile();
			exit;
		}
	}
}

register_error(elgg_echo('event_manager:event:file:notfound:text'));
forward(REFERER);