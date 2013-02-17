<?php
/**
 * Minds Web Services
 * Archive
 * 
 * @package Webservice
 * @author Mark Harding (mark@minds.com)
 *
 */
 
require_once(dirname(dirname(dirname(__FILE__))) ."/kaltura_video/kaltura/api_client/includes.php");

 
 /**
 * Web service to create a blank entry
 *
 * @return array (entry id, ks, uploadtoken)
 */
function archive_kaltura_create($filename, $filesize, $filetype) {
			
	$user = elgg_get_logged_in_user_entity();
	
	if(file_get_simple_type($filetype) == 'audio'){
		$mime_type = KalturaMediaType_AUDIO;
	} elseif(file_get_simple_type($filetype) == 'video'){
		$mime_type = KalturaMediaType_VIDEO;
	}
		
	$kmodel = KalturaModel::getInstance();
	$mediaEntry = new KalturaMediaEntry();
	$mediaEntry->name = 'Temporary Entry ID: '.time();
	$mediaEntry->mediaType = $mime_type;
	$mediaEntry->description = '';
	$mediaEntry->adminTags = KALTURA_ADMIN_TAGS;
	$mediaEntry = $kmodel->addMediaEntry($mediaEntry);
	
	$kmodel = KalturaModel::getInstance();
	$ks = $kmodel->getClientSideSession();
	
	$uploadToken = new KalturaUploadToken();
	$uploadToken->fileName = $filename;
	$uploadToken->fileSize = $filesize;
	$uploadToken = $kmodel->addUploadToken($uploadToken);

	$return = array( 'entryID'=> $mediaEntry->id,
					 'ks' => $ks,
					 'uploadToken' => $uploadToken->id
					);
					
	return $return;
}

expose_function('archive.kaltura.create',
				"archive_kaltura_create",
				array(	'filename' => array ('type' => 'string', 'required' => true),
					'filesize' => array ('type' => 'int', 'required' => true),
					'filetype' => array ('type' => 'string', 'required' => true),
					),
				"Create a kaltura entry",
				'POST',
				true,
				true);
				
/**
 * Web services to attach content to entry
 * 
 * @return bool true/false
 */
function archive_kaltura_link($entryID, $uploadToken) {
		
	$kmodel = KalturaModel::getInstance();
	
	$resource = new KalturaUploadedFileTokenResource();
	$resource->token = $uploadToken;
	$result = $kmodel->addContent($entryId, $resource);
	
	return $result;
}
expose_function('archive.kaltura.link',
				"archive_kaltura_link",
				array(	'entryID' => array ('type' => 'string', 'required' => true),
						'uploadToken' => array ('type' => 'string', 'required' => true),
					),
				"Link a kaltura upload token to an upload token",
				'POST',
				true,
				true);

/**
 * Web services to save an entry in to elgg
 * 
 * @return bool true/false
 */
function archive_kaltura_save($entryID, $title, $description, $tags, $license, $access) {
	
	$kmodel = KalturaModel::getInstance();

	$entry = $kmodel->getEntry($entryID);
	
	$ob = kaltura_get_entity($entryID);
	if(!$ob){
		$owner_guid = elgg_get_logged_in_user_guid();
	}
	
	$entry->name = strip_tags($title);
	$entry->description = $description;
	$entry->tags = $tags;

	$kmodel = KalturaModel::getInstance();
	$mediaEntry = new KalturaMediaEntry();
	$mediaEntry->name = $entry->name;
	$mediaEntry->description = $entry->description;
	$mediaEntry->tags = $entry->tags;
	$mediaEntry->adminTags = KALTURA_ADMIN_TAGS;
	$entry = $kmodel->updateMediaEntry($entryID,$mediaEntry);

		
	$tagarray = string_to_tag_array($tags);
	
	$ob = kaltura_update_object($entry,null,$access,$owner_guid,null,true, array('license'=> $license, 'thumbnail_sec'=>$thumbnail_sec));
	
	return true;
}
expose_function('archive.kaltura.save',
				"archive_kaltura_save",
				array(	'entryID' => array ('type' => 'string', 'required' => true),
						'title' => array ('type' => 'string', 'required' => true),
						'description' => array ('type' => 'string', 'required' => false),
						'tags' => array ('type' => 'string', 'required' => false, 'default'=>''),
						'license' => array ('type' => 'string', 'required' => true),
						'access' => array ('type' => 'int', 'required' => false, 'default'=> get_default_access()),
					),
				"save an entry to elgg",
				'POST',
				true,
				true);
