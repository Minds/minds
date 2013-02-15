<?php
/**
 * Minds Web Services
 * Archive
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
 
require_once(dirname(dirname(dirname(__FILE__))) ."/kaltura_video/kaltura/api_client/includes.php");

 
 /**
 * Web service to create a blank entry
 *
 * @return int ID of video
 */
function archive_kaltura_create_blank() {
			
	$user = elgg_get_logged_in_user_entity();
		
	$kmodel = KalturaModel::getInstance();
	$mediaEntry = new KalturaMediaEntry();
	$mediaEntry->name = 'Temporary Entry ID: '.time();
	$mediaEntry->description = '';
	$mediaEntry->adminTags = KALTURA_ADMIN_TAGS;
	
	return $mediaEntry->id;
}

expose_function('archive.kaltura.create_blank',
				"kaltura_web_service_get_videos_list",
				array(
					),
				"Create a blank kaltura entry",
				'POST',
				true,
				true);