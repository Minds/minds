<?php
/**
 * Elgg Webservices plugin 
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */

/**
 * Web service to get file list by all users
 *
 * @param string $context eg. all, friends, mine, groups
 * @param int $limit  (optional) default 10
 * @param int $offset (optional) default 0
 * @param int $group_guid (optional)  the guid of a group, $context must be set to 'group'
 * @param string $username (optional) the username of the user default loggedin user
 *
 * @return array $file Array of files uploaded
 */
function file_get_files($context,  $limit = 10, $offset = 0, $group_guid, $username) {	
	if(!$username) {
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
		if (!$user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}
		
		if($context == "all"){
		$params = array(
			'types' => 'object',
			'subtypes' => 'file',
			'limit' => $limit,
			'full_view' => FALSE,
		);
		}
		if($context == "mine" || $context == "user"){
		$params = array(
			'types' => 'object',
			'subtypes' => 'file',
			'owner_guid' => $user->guid,
			'limit' => $limit,
			'full_view' => FALSE,
		);
		}
		if($context == "group"){
		$params = array(
			'types' => 'object',
			'subtypes' => 'file',
			'container_guid'=> $group_guid,
			'limit' => $limit,
			'full_view' => FALSE,
		);
		}
		$latest_file = elgg_get_entities($params);
		
		if($context == "friends"){
		$latest_file = get_user_friends_objects($user->guid, 'file', $limit, $offset);
		}
	
	
	if($latest_file) {
		foreach($latest_file as $single ) {
			$file['guid'] = $single->guid;
			$file['title'] = $single->title;
			
			$owner = get_entity($single->owner_guid);
			$file['owner']['guid'] = $owner->guid;
			$file['owner']['name'] = $owner->name;
			$file['owner']['avatar_url'] = get_entity_icon_url($owner,'small');
			
			$file['container_guid'] = $single->container_guid;
			$file['access_id'] = $single->access_id;
			$file['time_created'] = (int)$single->time_created;
			$file['time_updated'] = (int)$single->time_updated;
			$file['last_action'] = (int)$single->last_action;
			$file['MIMEType'] = $single->mimetype;
			$file['file_icon'] = get_entity_icon_url($single,'small');
			$return[] = $file;
		}
	}
	else {
		$msg = elgg_echo('file:none');
		throw new InvalidParameterException($msg);
	}
	return $return;
}
	
expose_function('file.get_files',
				"file_get_files",
				array(
						'context' => array ('type' => 'string', 'required' => false, 'default' => 'all'),
					  'limit' => array ('type' => 'int', 'required' => false, 'default' => 10),
					  'offset' => array ('type' => 'int', 'required' => false, 'default' => 0),
					  'group_guid' => array ('type'=> 'int', 'required'=>false, 'default' =>0),
					   'username' => array ('type' => 'string', 'required' => false),
					),
				"Get file uploaded by all users",
				'GET',
				false,
				false);