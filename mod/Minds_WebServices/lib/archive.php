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
function archive_kaltura_create($filename, $filesize) {
			
	$user = elgg_get_logged_in_user_entity();
		
	$kmodel = KalturaModel::getInstance();
	$mediaEntry = new KalturaMediaEntry();
	$mediaEntry->name = 'Temporary Entry ID: '.time();
	$mediaEntry->description = '';
	$mediaEntry->adminTags = KALTURA_ADMIN_TAGS;
	
	$kmodel = KalturaModel::getInstance();
	$ks = $kmodel->getClientSideSession();
	
	$uploadToken = new KalturaUploadToken();
	$uploadToken->fileName = $filename;
	$uploadToken->fileSize = $filesize;
	$uploadToken = $kmedl->uploadToken->add($uploadToken);

	$return = array( 'entryID'=> $mediaEntry->id,
					 'ks' => $ks,
					 'uploadToken' => $uploadToken
					);
					
	return $mediaEntry->id;
}

expose_function('archive.kaltura.create',
				"archive_kaltura_create",
				array(	'filename' => array ('type' => 'string', 'required' => true),
					  	'filesize' => array ('type' => 'string', 'required' => true),
					),
				"Create a kaltura entry",
				'POST',
				true,
				true);