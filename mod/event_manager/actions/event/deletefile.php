<?php 

$guid = (int) get_input('guid');
$filename = get_input('file');

if(!empty($guid) && !empty($filename))
{
	if($entity = get_entity($guid))
	{
		if($entity->getSubtype() == Event::SUBTYPE)
		{
			$event = $entity;
		}
	}
	
	if($event->canEdit())
	{
		$files = json_decode($event->files, true);
		
		foreach($files as $index => $file) {
			
			if(strtolower($file["file"]) == strtolower($filename)) {
				$prefix = "events/".$event->getGUID()."/files/";
				
				$fileHandler = new ElggFile();
				$fileHandler->owner_guid = $event->owner_guid;
				$fileHandler->setFilename($prefix . $file->file);
				
				$fileHandler->delete();
				unset($files[$index]);
			}
		}
		
		$event->files = json_encode($files);
	}
	forward(REFERER);
}
	
register_error(elgg_echo('event_manager:event:file:notfound:text'));
forward(REFERER);