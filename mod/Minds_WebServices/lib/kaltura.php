<?php
/**
 * Minds Web Services
 * Kaltura
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
 
//elgg_load_library('archive:kaltura');
 
 /**
 * Web service to get a list of videos
 *
 * @param string $contet eg. own, friends or all (default all)
 * @param int $limit  (optional) default 10
 * @param int $offset (optional) default 0
 * @param string $username (optional) the username of the user default loggedin user
 *
 * @return array $return Array of videos
 */
function kaltura_web_service_get_videos_list($context,  $limit = 10, $offset = 0, $username) {	
		if(!$username) {
			$user = elgg_get_logged_in_user_entity();
		} else {
			$user = get_user_by_username($username);
			if (!$user) {
				throw new InvalidParameterException('registration:usernamenotvalid');
			}
		}
		
		if($context == 'all'){
			$videos = elgg_get_entities(array(
										'type' => 'object',
										'subtype' => 'kaltura_video',
										'limit' => $limit,
										'offset' => $offset,
										'full_view' => FALSE,
										));
		} elseif( $context == 'mine' || $context == 'user'){
			$videos = elgg_get_entities(array(
										'type' => 'object',
										'subtype' => 'kaltura_video',
										'owner_guid' => $user->guid,
										'limit' => $limit,
										'offset' => $offset,
										'full_view' => FALSE,
										));
		} elseif( $context == 'friends'){
			$videos = get_user_friends_objects($user->guid, 'kaltura_video', $limit, $offset);
		}
		
		if($videos){
			foreach($videos as $single ) {
				$video['guid'] = $single->guid;
				$video['title'] = $single->title;
				
				$video['video_id'] = $single->kaltura_video_id;
				$video['thumbnail'] = $single->kaltura_video_thumbnail;
	
				$owner = get_entity($single->owner_guid);
				$video['owner']['guid'] = $owner->guid;
				$video['owner']['name'] = $owner->name;
				$video['owner']['username'] = $owner->username;
				$video['owner']['avatar_url'] = $owner->getIconUrl('small');
				
				$video['container_guid'] = $single->container_guid;
				$video['access_id'] = $single->access_id;
				$video['time_created'] = (int)$single->time_created;
				$video['time_updated'] = (int)$single->time_updated;
				$video['last_action'] = (int)$single->last_action;
				$return[] = $video;
			}
	
		} else {
			$msg = elgg_echo('kalturavideo:none');
			throw new InvalidParameterException($msg);
		}
	
	return $return;
}

expose_function('kaltura.get_list',
				"kaltura_web_service_get_videos_list",
				array(
						'context' => array ('type' => 'string', 'required' => false, 'default' => 'all'),
					  	'limit' => array ('type' => 'int', 'required' => false, 'default' => 10),
					  	'offset' => array ('type' => 'int', 'required' => false, 'default' => 0),
					   	'username' => array ('type' => 'string', 'required' => false),
					),
				"Get list of videos",
				'GET',
				false,
				false);

 
 /**
 * Web service to get more info
 *
 * @param string $video_id the id of the kaltura video
 *
 * @return array $return information of the kaltura video
 */
function kaltura_web_service_get_video($video_id) {	
	
	$video = get_entity($video_id) ? get_entity($video_id) : kaltura_get_entity($video_id);		

	if($video){
		
				$return['guid'] = $video->guid;
				$return['title'] = $video->title;
				
				$return['video_id'] = $video->kaltura_video_id;
				$return['thumbnail'] = $video->kaltura_video_thumbnail;
	
				$owner = get_entity($video->owner_guid);
				$return['owner']['guid'] = $owner->guid;
				$return['owner']['name'] = $owner->name;
				$return['owner']['username'] = $owner->username;
				$return['owner']['avatar_url'] = $owner->getIconUrl('small');
				
				$return['container_guid'] = $video->container_guid;
				$return['access_id'] = $video->access_id;
				$return['time_created'] = (int)$video->time_created;
				$return['time_updated'] = (int)$video->time_updated;
				$return['last_action'] = (int)$video->last_action;
		
	
		} else {
			$msg = elgg_echo('kalturavideo:none');
			throw new InvalidParameterException($msg);
		}
	
	return $return;
}

expose_function('kaltura.get_video',
				"kaltura_web_service_get_video",
				array(
					   	'video_id' => array ('type' => 'string'),
					),
				"Get information about a video",
				'GET',
				false,
				false);
				
/**
 * Web service to upload a new video to Kaltura
 *
 * @param string $title - title of the video
 * @param string $description - description of the video
 * @param string $license - license of the video
 * @param int $container_guid (optional)
 *
 * @return bool true / false
 * 
 */ 
 function kaltura_web_service_new_video($title, $description, $license, $container_guid) {	
		
		//$user = elgg_get_logged_in_user_entity();
		$user = get_user_by_username('mark');
		login($user);
		
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
		 
		  	$ob = kaltura_update_object($mixEntry,null,ACCESS_PRIVATE,$user->getGuid(),0, false, array('uploaded_id' => $mediaEntry->id, 'license' => $license));
		  
		  if($ob){
			  return true;
		  } else {
			  $msg = elgg_echo('kalturavideo:upload:error');
				throw new InvalidParameterException($msg);
		  }
 }
 
expose_function('kaltura.new_video',
				"kaltura_web_service_new_video",
				array(
					   	'title' => array ('type' => 'string'),
						'description' => array ('type' => 'string'),
						'license' => array ('type' => 'string'),
						'container_guid' => array ('type' => 'string', 'required' => false),
					),
				"Upload a new video",
				'post',
				false,
				false);
				
/**
 * Web service to delete a video on  Kaltura
 *
 * @param string $video_id
 *
 * @return bool true / false
 * 
 */ 
 function kaltura_web_service_delete_video($video_id) {	
		$ob = kaltura_get_entity($video_id);
		
		//check if belongs to this user (or is admin)
		if($ob->canEdit()) {
			$kmodel = KalturaModel::getInstance();
			//open the kaltura list without admin privileges
			$entry = $kmodel->getEntry ( $video_id );
			if($entry instanceof KalturaMixEntry) {
				//deleting media related
				//TODO: MAYBE should ask before do this!!!
				$list = $kmodel->listMixMediaEntries($video_id);
				//print_r($list);die;
				foreach($list as $subEntry) {
					$kmodel->deleteEntry($subEntry->id);
				}
				//Delete the mix
				$kmodel->deleteEntry ( $video_id );
				$ob = kaltura_get_entity($video_id);
				if($ob) $ob->delete();
				return str_replace("%ID%",$delete_video,elgg_echo("kalturavideo:action:deleteok"));
			}
			else {
				$error = str_replace("%ID%",$delete_video,elgg_echo("kalturavideo:action:deleteko"));
				throw new InvalidParameterException($msg);
			}
		}
		else {
			$error = elgg_echo('kalturavideo:edit:notallowed');
			throw new InvalidParameterException($msg);
		}
 }
 			
expose_function('kaltura.delete_video',
				"kaltura_web_service_delete_video",
				array(
					   	'video_id' => array ('type' => 'string'),
					),
				"Delete a video",
				'GET',
				true,
				true);
				
/**
 * Web service to rate a video on Kaltura
 *
 * @param string $guid - title of the video
 * @param string $rate - description of the video
 *
 * @return bool true / false
 * 
 */ 
 function kaltura_web_service_rate_video($guid, $rate) {	
 
 	// Get the post
	if ($entity = get_entity($guid)) {
		$metadata = kaltura_get_metadata($entity);
		if($metadata->kaltura_video_rating_on == 'Off') {
			unset($entity);
		}
	}
	
 	$user = elgg_get_logged_in_user_entity();
	
 	// Get old rating
	list($numvotes,$image,$oldrate) = kaltura_get_rating($entity);
	
	// Calculate new rating
	$oldrate = ($oldrate * $numvotes);
	$newrate = ($oldrate + $rate);
	$newcount = ($numvotes + 1.00);
	$newrate = ($newrate / $newcount);

	//do no rate if is already rated
	if(!kaltura_is_rated_by_user($guid,$user,$numvotes)) {
		// Delete old ratings
		$kaltura_video_ratings = $entity->getAnnotations('kaltura_video_rating');
		foreach ($kaltura_video_ratings as $kaltura_video_rating){
			$rating_id = $kaltura_video_rating['id'];
			$ratingobject = get_annotation($rating_id);
			$ratingobject->delete();
		}

		$kaltura_video_numvotes = $entity->getAnnotations('kaltura_video_numvotes');
		foreach ($kaltura_video_numvotes as $kaltura_video_numvote){
			$numvotes_id = $kaltura_video_numvote['id'];
			$numvotesobject = get_annotation($numvotes_id);
			$numvotesobject->delete();
		}

		// Save new rating
		$entity->annotate('kaltura_video_rating', $newrate, ACCESS_PUBLIC, $owner, "integer");
		$entity->annotate('kaltura_video_numvotes', $newcount, ACCESS_PUBLIC, $owner, "integer");
		// Save this vote to avoid new duplicate votes
		$_SESSION['user']->annotate('kaltura_video_rated', $guid);

		//add to the river
		add_to_river('river/object/kaltura_video/rate','rate',$user->getGUID(),$entity->getGUID());

		return elgg_echo("kalturavideo:ratesucces");

	}
	else {
			$msg = elgg_echo('kalturavideo:notrated');
			throw new InvalidParameterException($msg);
	}
 }
 
 expose_function('kaltura.rate_video',
				"kaltura_web_service_rate_video",
				array(
					   	'guid' => array ('type' => 'int'),
						'rate' => array ('type' => 'int'),
					),
				"Rate a video",
				'GET',
				true,
				true);
