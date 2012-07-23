<?php
/**
 * Minds Web Services
 * Kaltura
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
 
 
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
										'full_view' => FALSE,
										));
		} elseif( $context == 'mine' || $context == 'user'){
			$videos = elgg_get_entities(array(
										'type' => 'object',
										'subtype' => 'kaltura_video',
										'owner_guid' => $user->guid,
										'limit' => $limit,
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