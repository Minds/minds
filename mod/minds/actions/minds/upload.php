<?php
/**
 * Elgg file uploader/edit action
 *
 * @package ElggFile
 */
action_gatekeeper();
require_once(dirname(dirname(dirname(dirname(__FILE__)))) ."/kaltura_video/kaltura/api_client/includes.php");
elgg_load_library('tidypics:upload');

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

elgg_make_sticky_form('generic-upload');

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
		//find the users uploads album
		$albums = elgg_get_entities_from_metadata(array(
													'type'=> 'object',
													'subtype' => 'album',
													'owner_guid' => elgg_get_logged_in_user_guid(),
													'metadata_name_value_pairs' => array('name'=>'uploads', 'value'=>true)
													
										));
		$album = $albums[0];
		//if the album cant be found then lets create one
		if (!$album) {
			$album = new TidypicsAlbum();
			$album->owner_guid = elgg_get_logged_in_user_guid();
			$album->title = 'Uploads';
			$album->access_id = 1;
			$album->uploads = true;
			
			if (!$album->save()) {
				register_error(elgg_echo("album:error"));
				forward(REFERER);
			}
		}
		
		// post limit exceeded
		if (count($_FILES) == 0) {
			trigger_error('Tidypics warning: user exceeded post limit on image upload', E_USER_WARNING);
			register_error(elgg_echo('tidypics:exceedpostlimit'));
			forward(REFERER);
		}
		
		$mime = $_FILES['upload']['type'];
		$image = new TidypicsImage();
		$image->title = $title;
		$image->description = $description;
		$image->container_guid = $album->getGUID();
		$image->setMimeType($mime);
		$image->tags = $tags;
		$image->access_id = $album->access_id;
	
		$result = $image->save($_FILES['upload']);
		//error_log('Save: ' . $image->getGUID());
		if ($result) {
			//array_push($uploaded_images, $image->getGUID());
			$album->prependImageList(array($image->getGUID()));
		
			add_to_river('river/object/image/create', 'create', $image->getOwnerGUID(), $image->getGUID());
			
			forward($image->getURL());
		}
										
	} else {
		//anything else should be forwarded to files
		$file = new FilePluginFile();
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
	}
