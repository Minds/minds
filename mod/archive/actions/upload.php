<?php
/**
 * Elgg file uploader/edit action
 *
 * @package ElggFile
 */
action_gatekeeper();
elgg_load_library('archive:kaltura');
elgg_load_library('tidypics:upload');

// Get variables
$title = get_input("title");
$desc = get_input("description");
$access_id = (int) get_input("access_id");
$container_guid = (int) get_input('container_guid', 0);
$guid = (int) get_input('file_guid');
$tags = get_input("tags");
$license = get_input("license");
$category = get_input("category");

if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
}
$user_guid = $_SESSION['user']->getGUID();

elgg_make_sticky_form('generic-upload');
if($license == 'not-selected'){
	register_error(elgg_echo('minds:license:not-selected'));
	forward(REFERER);
}
// check if upload failed
if (!$_FILES['upload']['name']) {
	register_error(elgg_echo('minds:upload:nofile'));
	forward(REFERER);
}
	
	$mime_type = $_FILES['upload']['type'];
	//if the file is a video then we upload to kaltura!
	if(file_get_simple_type($mime_type) == 'video' || file_get_simple_type($mime_type) == 'audio'){
		$kmodel = KalturaModel::getInstance();
		$ks = $kmodel->getClientSideSession();

		$mediaEntry = new KalturaMediaEntry();
		$mediaEntry->name = strip_tags($title);
		$mediaEntry->description = $desc;
		$mediaEntry->tags = $tags;
		$mediaEntry->mediaType = file_get_simple_type($mime_type) == 'video' ? KalturaMediaType_VIDEO : KalturaMediaType_AUDIO;

		$mediaEntry = $kmodel->addMediaEntry($mediaEntry, $_FILES['upload']['tmp_name']);
			
		$ob = kaltura_update_object($mediaEntry,null,$access_id,$user_guid,$container_guid, false, array('title' => $title, 'uploaded_id' => $mediaEntry->id, 'license' => $license));
		  
		  if($ob){
			  elgg_clear_sticky_form('file');
			  system_message(str_replace("%ID%",$video_id,elgg_echo("kalturavideo:action:updatedok")));
			  forward($ob->getURL());
			  return true;
		  } else {
			  register_error(str_replace("%ID%",$video_id,elgg_echo("kalturavideo:action:updatedko"))."\n$error");
			  return false;
		  }
	
	} elseif (file_get_simple_type($mime_type) == 'image'){
// If Image then create an album. Don't upload to Kaltura.
 	if ($guid){
       		$image = get_entity($guid, 'object');
	} else {
        	$image = new TidypicsImage();
    	}

	if($album_guid){
		$album = get_entity($album_guid, 'object');
	} else {
		$album = $image->getContainerEntity();
	}
	
	if(!$album || 	!elgg_instanceof($album,'object','album')){
		$albums = elgg_get_entities(array( 	'type'=> 'object',
							'subtypes' => array('album'),
							'owner_guid' => elgg_get_logged_in_user_guid(),
							));
		$album = $albums[0];
	}

	//still no album... @todo make this into the core album functionality

	if (!$album) {
		$album = new TidypicsAlbum();
		$album->owner_guid = elgg_get_logged_in_user_guid();
		$album->title = 'Uploads';
		$album->access_id = 2;
		$album->uploads = true;
			
		if (!$album->save()) {
			register_error(elgg_echo("album:error"));
			forward(REFERER);
		}
	}

    $image->title = $title;
    $image->description = $desc;
	$image->super_sybtype = 'archive';
    $image->container_guid = $album->getGUID();
    //$image->setMimeType($mime_type);
    $image->tags = $tags;
    $image->access_id = $access_id;
    $image->license = $license;
//    $image->category = $category; //No category

    $guid = $image->save($_FILES['upload']);
    
    if ($guid) {
        $album->prependImageList(array($guid));
	echo $guid;
        add_to_river('river/object/image/create', 'create', $image->getOwnerGUID(), $image->getGUID());
    		return true;
	}								
	} else {
		//anything else should be forwarded to files
		$file = new ElggFile();
		$file->subtype = "file";
	
		// if no title on new upload, grab filename
		if (empty($title)) {
			$title = $_FILES['upload']['name'];
		}
		
		$file->title = $title;
		$file->description = $desc;
		$file->access_id = $access_id;
		$file->container_guid = $container_guid;
		$file->tags = $tags;
		$file->license = $license;
		
		$tags = explode(",", $tags);
		$file->tags = $tags;
		
		// we have a file upload, so process it
		if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {
		
			$prefix = "file/";
			$filestorename = elgg_strtolower(time().$_FILES['upload']['name']);
			
		
			$mime_type = $file->detectMimeType($_FILES['upload']['tmp_name'], $_FILES['upload']['type']);
			$file->setFilename($prefix . $filestorename);
			$file->setMimeType($mime_type);
			$file->originalfilename = $_FILES['upload']['name'];
			$file->simpletype = file_get_simple_type($mime_type);
			
			//save the space so we can add it to our quota. 
			$file->size = $_FILES['upload']['size'];
		
			// Open the file to guarantee the directory exists
			$file->open("write");
			$file->close();
			move_uploaded_file($_FILES['upload']['tmp_name'], $file->getFilenameOnFilestore());
		
			$guid = $file->save();
		
			// if image, we need to create thumbnails (this should be moved into a function)
			if ($guid && $file->simpletype == "image") {
				$file->icontime = time();
				
				$thumbnail = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 60, 60, true);
				if ($thumbnail) {
					$thumb = new ElggFile();
					$thumb->setMimeType($_FILES['upload']['type']);
		
					$thumb->setFilename($prefix."thumb".$filestorename);
					$thumb->open("write");
					$thumb->write($thumbnail);
					$thumb->close();
		
					$file->thumbnail = $prefix."thumb".$filestorename;
					unset($thumbnail);
				}
		
				$thumbsmall = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 153, 153, true);
				if ($thumbsmall) {
					$thumb->setFilename($prefix."smallthumb".$filestorename);
					$thumb->open("write");
					$thumb->write($thumbsmall);
					$thumb->close();
					$file->smallthumb = $prefix."smallthumb".$filestorename;
					unset($thumbsmall);
				}
		
				$thumblarge = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 600, 600, false);
				if ($thumblarge) {
					$thumb->setFilename($prefix."largethumb".$filestorename);
					$thumb->open("write");
					$thumb->write($thumblarge);
					$thumb->close();
					$file->largethumb = $prefix."largethumb".$filestorename;
					unset($thumblarge);
				}
			}
		} else {
			// not saving a file but still need to save the entity to push attributes to database
			$file->save();
		}
		
		if ($guid) {
			$message = elgg_echo("file:saved");
			system_message($message);
			add_to_river('river/object/file/create', 'create', elgg_get_logged_in_user_guid(), $file->guid);
			forward($file->getURL());
		} else {
			// failed to save file object - nothing we can do about this
			$error = elgg_echo("file:uploadfailed");
			register_error($error);
		}
	
		$container = get_entity($container_guid);
		if (elgg_instanceof($container, 'group')) {
			forward("archive/group/$container->guid/all");
		} else {
			forward("archive/$container->username");
		}
	}
