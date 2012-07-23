<?php 

	$guid = (int) get_input("guid");
	$title = get_input("title");
	
	if(!empty($guid) && ($event = get_entity($guid)))
	{
		if($event->getSubtype() == Event::SUBTYPE)
		{
			if(!$event->canEdit())
			{
				forward($event->getURL());
			}
			
			if(empty($title) || (!isset($_FILES['file']['name']) || empty($_FILES['file']['name'])))
			{
				register_error(elgg_echo("event_manager:action:event:edit:error_fields"));
				forward(REFERER);	
			}
			else
			{
				if(empty($event->files))
				{
					$filesArray = array();
				}
				else
				{
					$filesArray = json_decode($event->files, true);
				}
				
				$prefix = "events/".$event->getGUID()."/files/";
				
				$newFilename = sanitize_filename($_FILES['file']['name']);
								
				$fileHandler = new ElggFile();
				$fileHandler->setFilename($prefix . $newFilename);
				$fileHandler->owner_guid = $event->owner_guid;
				$fileHandler->open("write");
				$fileHandler->write(get_uploaded_file('file'));
				$fileHandler->close();
				
				$filesArray[] = array(	'title' => $title, 
										'file' => $newFilename, 
										'mime' => $_FILES['file']['type'], 
										'time_uploaded' => time(), 
										'uploader' => elgg_get_logged_in_user_guid());
				
				$event->files = json_encode($filesArray);
				
				system_message(elgg_echo("event_manager:action:event:edit:ok"));
				forward(REFERER);
			}
		}
	}
	
	register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
	forward(REFERER);