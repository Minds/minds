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

if (empty($entity->title)) {
	register_error(elgg_echo("album:blank"));
	forward(REFERER);
}
if($entity->license == 'not-selected'){
	register_error(elgg_echo('minds:license:not-selected'));
	forward(REFERER);
}

if($entity->getSubtype() == 'kaltura_video'){
	$video_id = $entity->kaltura_video_id;
} elseif($entity->getSubtype() == 'image'){
	$entity->save();
        forward($entity->getURL());
        return true;	
} elseif($entity->getSubtype() == 'album'){
	$entity->save();
	forward($entity->getURL());
	return true;
} elseif($entity->getSubtype() == 'file'){

	// we have a file upload, so process it
	if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {
	
		$prefix = "file/";
	
		// if previous file, delete it
		$filename = $entity->getFilenameOnFilestore();
		if (file_exists($filename)) {
			unlink($filename);
		}

		// use same filename on the disk - ensures thumbnails are overwritten
		$filestorename = $entity->getFilename();
		$filestorename = elgg_substr($filestorename, elgg_strlen($prefix));
		
		$entity->setFilename($prefix . $filestorename);
		$mime_type = ElggFile::detectMimeType($_FILES['upload']['tmp_name'], $_FILES['upload']['type']);
		
		// hack for Microsoft zipped formats
		$info = pathinfo($_FILES['upload']['name']);
		$office_formats = array('docx', 'xlsx', 'pptx');
		if ($mime_type == "application/zip" && in_array($info['extension'], $office_formats)) {
			switch ($info['extension']) {
				case 'docx':
					$mime_type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
					break;
				case 'xlsx':
					$mime_type = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
					break;
				case 'pptx':
					$mime_type = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
					break;
			}
		}
		
		// check for bad ppt detection
		if ($mime_type == "application/vnd.ms-office" && $info['extension'] == "ppt") {
			$mime_type = "application/vnd.ms-powerpoint";
		}
		
		$entity->setMimeType($mime_type);
		$entity->originalfilename = $_FILES['upload']['name'];
		$entity->simpletype = file_get_simple_type($mime_type);
		
		// Open the file to guarantee the directory exists
		$entity->open("write");
		$entity->close();
		move_uploaded_file($_FILES['upload']['tmp_name'], $entity->getFilenameOnFilestore());
		
		$entity->save();
		
		forward($entity->getURL());
	
	} else {
		// not saving a file but still need to save the entity to push attributes to database
		$entity->save();
		forward($entity->getURL());
	}
	
	return true;
}

$title = get_input('title');
$desc = get_input('description');
$license = get_input('license');
$entity->tags = get_input('tags');
$entity->thumbnail_sec = get_input('thumbnail_selector');
$entity->access = get_input('access_id');

if($license == 'not-selected'){
	register_error(elgg_echo('minds:license:not-selected'));
	forward(REFERER);
}

if($video_id) {

	$error = '';
	//check the video

	try {
		$kmodel = KalturaModel::getInstance();

		$entry = $kmodel->getEntry($video_id);
	
	}
	catch(Exception $e) {
		$error = $e->getMessage();
	}


	if(empty($error)) {
		// Convert string of tags into a preformatted array
		$tagarray = string_to_tag_array($tags);

		$entry->name = strip_tags($title);
		$entry->description = $desc;

		if (is_array($tagarray)) {
			$entry->tags = implode(", ",$tagarray);
		}
		try {
			$kmodel = KalturaModel::getInstance();
			$mediaEntry = new KalturaMediaEntry();
			$mediaEntry->name = $entry->name;
			$mediaEntry->description = $entry->description;
			$mediaEntry->tags = $entry->tags;
			$mediaEntry->adminTags = KALTURA_ADMIN_TAGS;
			$entry = $kmodel->updateMediaEntry($video_id,$mediaEntry);
		}
		catch(Exception $e) {
			$error = $e->getMessage();
		}

		if(empty($error)) {
			$entity->save();
			forward($entity->getURL());
		}else {
			register_error($error);
		}
	}
	if($error) {
		register_error(str_replace("%ID%",$video_id,elgg_echo("kalturavideo:action:updatedko"))."\n$error");
	}
	else {
	

		system_message(str_replace("%ID%",$video_id,elgg_echo("kalturavideo:action:updatedok")));
	}
}
