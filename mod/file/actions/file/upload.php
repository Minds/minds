<?php
/**
 * Elgg file uploader/edit action
 *
 * @package ElggFile
 */
 
require_once(dirname(dirname(dirname(dirname(__FILE__)))) ."/kaltura_video/kaltura/api_client/includes.php");

// Get variables
$title = get_input("title");
$desc = get_input("description");
$access_id = (int) get_input("access_id");
$container_guid = (int) get_input('container_guid', 0);
$guid = (int) get_input('file_guid');
$tags = get_input("tags");
$license = get_input("license");

if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
}
$user_guid = $_SESSION['user']->getGUID();

elgg_make_sticky_form('file');

// check if upload failed
if (!empty($_FILES['upload']['name']) && $_FILES['upload']['error'] != 0) {
	register_error(elgg_echo('file:cannotload'));
	forward(REFERER);
}

// check whether this is a new file or an edit
$new_file = true;
if ($guid > 0) {
	$new_file = false;
}

if ($new_file) {
	// must have a file if a new file upload
	if (empty($_FILES['upload']['name'])) {
		$error = elgg_echo('file:nofile');
		register_error($error);
		forward(REFERER);
	}
	
	$mime_type = $_FILES['upload']['type'];
	//if the file is a video then we upload to kaltura!
	if(file_get_simple_type($mime_type) == 'video' || file_get_simple_type($mime_type) == 'audio'){
		$kmodel = KalturaModel::getInstance();
		$ks = $kmodel->getClientSideSession();

		$mediaEntry = new KalturaMediaEntry();
		$mediaEntry->name = $title;
		$mediaEntry->description = $description;
		$mediaEntry->mediaType = file_get_simple_type($mime_type) == 'video' ? KalturaMediaType_VIDEO : KalturaMediaType_AUDIO;

		$mediaEntry = $kmodel->addMediaEntry($mediaEntry, $_FILES['upload']['tmp_name']);
	
		$ob = kaltura_update_object($mediaEntry,null,ACCESS_PRIVATE,$user_guid,$container_guid, false, array('uploaded_id' => $mediaEntry->id, 'license' => $license));
		  
		  if($ob){
			  elgg_clear_sticky_form('file');
			  system_message(str_replace("%ID%",$video_id,elgg_echo("kalturavideo:action:updatedok")));
			  forward($ob->getURL());
			  return true;
		  } else {
			  register_error(str_replace("%ID%",$video_id,elgg_echo("kalturavideo:action:updatedko"))."\n$error");
			  return false;
		  }
	
	} else {

		$file = new FilePluginFile();
		$file->subtype = "file";
	
		// if no title on new upload, grab filename
		if (empty($title)) {
			$title = $_FILES['upload']['name'];
		}
	
	}

} else {
	// load original file object
	$file = new FilePluginFile($guid);
	if (!$file) {
		register_error(elgg_echo('file:cannotload'));
		forward(REFERER);
	}

	// user must be able to edit file
	if (!$file->canEdit()) {
		register_error(elgg_echo('file:noaccess'));
		forward(REFERER);
	}

	if (!$title) {
		// user blanked title, but we need one
		$title = $file->title;
	}
}

$file->title = $title;
$file->description = $desc;
$file->access_id = $access_id;
$file->container_guid = $container_guid;
$file->license = $license;

$tags = explode(",", $tags);
$file->tags = $tags;

// we have a file upload, so process it
if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {

	$prefix = "file/";

	// if previous file, delete it
	if ($new_file == false) {
		$filename = $file->getFilenameOnFilestore();
		if (file_exists($filename)) {
			unlink($filename);
		}

		// use same filename on the disk - ensures thumbnails are overwritten
		$filestorename = $file->getFilename();
		$filestorename = elgg_substr($filestorename, elgg_strlen($prefix));
	} else {
		$filestorename = elgg_strtolower(time().$_FILES['upload']['name']);
	}

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

// file saved so clear sticky form
elgg_clear_sticky_form('file');


// handle results differently for new files and file updates
if ($new_file) {
	if ($guid) {
		$message = elgg_echo("file:saved");
		system_message($message);
		add_to_river('river/object/file/create', 'create', elgg_get_logged_in_user_guid(), $file->guid);
	} else {
		// failed to save file object - nothing we can do about this
		$error = elgg_echo("file:uploadfailed");
		register_error($error);
	}

	$container = get_entity($container_guid);
	if (elgg_instanceof($container, 'group')) {
		forward("file/group/$container->guid/all");
	} else {
		forward("file/owner/$container->username");
	}

} else {
	if ($guid) {
		system_message(elgg_echo("file:saved"));
	} else {
		register_error(elgg_echo("file:uploadfailed"));
	}

	forward($file->getURL());
}	