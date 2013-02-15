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
		
	$kmodel = KalturaModel::getInstance();
	$mediaEntry = new KalturaMediaEntry();
	$mediaEntry->name = 'Temporary Entry ID: '.time();
	$mediaEntry->mediaType = file_get_simple_type($mime_type) == 'audio' ? KalturaMediaType_AUDIO : KalturaMediaType_VIDEO;
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
