<?php
/**
 * Elgg file uploader/edit action
 *
 * @package ElggFile
 */
 
require_once(dirname(dirname(dirname(__FILE__))) ."/kaltura_video/kaltura/api_client/includes.php");

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
	
	$mime_type = $_FILES['upload']['type'];
	//if the file is a video then we upload to kaltura!
	if(file_get_simple_type($mime_type) == 'video'){
		$kmodel = KalturaModel::getInstance();
		$ks = $kmodel->getClientSideSession();
		
		//setup the blank mix entry
		try {
		    $mixEntry = new KalturaMixEntry();
		    $mixEntry->name = $title;
		    $mixEntry->editorType = KalturaEditorType_SIMPLE;
		    $mixEntry->adminTags = KALTURA_ADMIN_TAGS;
		    $mixEntry = $kmodel->addMixEntry($mixEntry);
		    $entryId = $mixEntry->id;
		}
		catch(Exception $e) {
			$error = $e->getMessage();
		}
	
	
			$mediaEntry = new KalturaMediaEntry();
		    $mediaEntry->name = $title;
		    $mediaEntry->description = $description;
		    $mediaEntry->mediaType = KalturaMediaType_VIDEO;

		    $mediaEntry = $kmodel->addMediaEntry($mediaEntry, $_FILES['upload']['tmp_name']);
	
		 
		 
		  	$ob = kaltura_update_object($mixEntry,null,ACCESS_PRIVATE,$user_guid,$container_guid, false, array('uploaded_id' => $mediaEntry->id, 'license' => $license));
		  
		  if($ob){
			  elgg_clear_sticky_form('file');
			  system_message(str_replace("%ID%",$video_id,elgg_echo("kalturavideo:action:updatedok")));
			  forward($ob->getURL());
			  return true;
		  } else {
			  register_error(str_replace("%ID%",$video_id,elgg_echo("kalturavideo:action:updatedko"))."\n$error");
			  return false;
		  }
		  
	}