<?php
/**
 * Elgg Webservices plugin 
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
 
 /**
 * Web service for making a wall post
 *
 * @param string $to username of user to recieve wall post
 * @param string $message     the content of wall post
 * @param string $access    access level for post{-1, 0, 1, 2, -2}
 * @param string $password password of user
 *
 * @return bool
 */
function wall_post($message, $access = ACCESS_PUBLIC, $wall_method = "api",	$to,  $username) {
	if(!$username) {
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
		if (!$user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}
	
	if(!$to) {
		$to_user = get_loggedin_user();
	} else {
		$to_user = get_user_by_username($to_user);
		if (!$to_user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}
	
	$post = new WallPost;
	$post->to_guid = $to_user->guid;
	$post->owner_guid = $user->guid;
	$post->message = $message;
	$post->method = $wall_method;
	$post->access_id = strip_tags($access);
	
	$guid = $post->save();
	
	if (!$guid) {
		register_error(elgg_echo("wall:error"));
		$return['success'] = false;
	} else {
		
		//add the message
		add_to_river('river/object/wall/create', 'create', $user->guid, $guid);
	
		notification_create(array($to_user->guid), $user->guid, $guid, array('description'=>$message,'notification_view'=>'wall'));
		
		$return['guid'] = $post->guid;
		
		$owner = get_entity($post->owner_guid, 'user');
		$return['owner']['guid'] = $owner->guid;
		$return['owner']['name'] = $owner->name;
		$return['owner']['avatar_url'] = get_entity_icon_url($owner,'small');
			
		$return['time_created'] = (int)$post->time_created;
		$return['message'] = $post->message;
	}
	
	return $return;
} 
				
expose_function('wall.post',
				"wall_post",
				array(	
						'message' => array ('type' => 'string'),
						'access' => array ('type' => 'string', 'required' => false),
						'wall_method' => array ('type' => 'string', 'required' => false),
						'to' => array ('type' => 'string', 'required' => false),
						'username' => array ('type' => 'string', 'required' => false),
					),
				"Post to the wall",
				'POST',
				true,
				true);
				
/**
 * Web service for getting a list of wall posts
 *
 * @param string $context all/mine/friends
 * @param string $username username of author
 *
 * @return bool
 */
function wall_get($context, $limit = 10, $offset = 0, $username) {
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
			'subtypes' => 'wallpost',
			'limit' => $limit,
			'full_view' => FALSE,
		);
		}
		if($context == "mine" || $context == "user"){
		$params = array(
			'types' => 'object',
			'subtypes' => 'wallpost',
			'owner_guid' => $user->guid,
			'limit' => $limit,
			'full_view' => FALSE,
		);
		}
		$posts = elgg_get_entities($params);
		
		if($context == "friends"){
		$posts = get_user_friends_objects($user->guid, 'wallpost', $limit, $offset);
		}

if($posts){
	foreach($posts as $single ) {
		$post['guid'] = $single->guid;
		
		$owner = get_entity($single->owner_guid);
		$post['owner']['guid'] = $owner->guid;
		$post['owner']['name'] = $owner->name;
		$post['owner']['avatar_url'] = get_entity_icon_url($owner,'small');
			
		$post['time_created'] = (int)$single->time_created;
		$post['message'] = $single->message;
		$return[] = $post;
	} 
} else {
		$msg = elgg_echo('wall:noposts');
		throw new InvalidParameterException($msg);
	}
	
	return $return;
	} 
				
expose_function('wall.get',
				"wall_get",
				array(	'context' => array ('type' => 'string', 'required' => false, 'default' => 'all'),
						'limit' => array ('type' => 'int', 'required' => false),
						'offset' => array ('type' => 'int', 'required' => false),
						'username' => array ('type' => 'string', 'required' =>false),
					),
				"Get wall posts",
				'GET',
				false,
				false);
				
				
/**
 * Web service to delete a wall post
 *
 * @param string $username username
 * @param string $guid   GUID of wire post to delete
 *
 * @return bool
 */
function wall_delete($username, $guid) {
	$user = get_user_by_username($username);
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	
	$wallpost = get_entity($guid);
	$return['success'] = false;
	if ($wallpost->getSubtype() == "wallpost" && $wallpost->canEdit($user->guid)) {
		$wallpost->delete();
		$return['success'] = true;
	} else {
		$return['message'] = elgg_echo("wall:notdeleted");
	}
	return $return;
} 
				
expose_function('wall.delete',
				"wall_delete",
				array('username' => array ('type' => 'string'),
						'wireid' => array ('type' => 'int'),
					),
				"Delete a wall post",
				'POST',
				true,
				true);