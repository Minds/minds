<?php
/**
 * Elgg Webservices plugin 
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
 
 /**
 * Web service for making a wire post
 *
 * @param string $username username of author
 * @param string $text     the content of wire post
 * @param string $acess    access level for post{-1, 0, 1, 2, -2}
 * @param string $password password of user
 *
 * @return bool
 */
function wire_save_post($text, $access = ACCESS_PUBLIC, $wireMethod = "api", $username) {
	if(!$username) {
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
		if (!$user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}
	
	$return['success'] = false;
	if (empty($text)) {
		$return['message'] = elgg_echo("thewire:blank");
		return $return;
	}
	$access_id = strip_tags($access);
	$guid = thewire_save_post($text, $user->guid, $access_id, $wireMethod);
	if (!$guid) {
		$return['message'] = elgg_echo("thewire:error");
		return $return;
	}
	$return['success'] = true;
	return $return;
	} 
				
expose_function('wire.save_post',
				"wire_save_post",
				array(
						'text' => array ('type' => 'string'),
						'access' => array ('type' => 'string', 'required' => false),
						'wireMethod' => array ('type' => 'string', 'required' => false),
						'username' => array ('type' => 'string', 'required' => false),
					),
				"Post a wire post",
				'POST',
				true,
				true);
				
/**
 * Web service for read latest wire post of user
 *
 * @param string $context all/mine/friends
 * @param string $username username of author
 *
 * @return bool
 */
function wire_get_posts($context, $limit = 10, $offset = 0, $username) {
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
			'subtypes' => 'thewire',
			'limit' => $limit,
			'full_view' => FALSE,
		);
		}
		if($context == "mine" || $context == "user"){
		$params = array(
			'types' => 'object',
			'subtypes' => 'thewire',
			'owner_guid' => $user->guid,
			'limit' => $limit,
			'full_view' => FALSE,
		);
		}
		$latest_wire = elgg_get_entities($params);
		
		if($context == "friends"){
		$latest_wire = get_user_friends_objects($user->guid, 'thewire', $limit, $offset);
		}

if($latest_wire){
	foreach($latest_wire as $single ) {
		$wire['guid'] = $single->guid;
		
		$owner = get_entity($single->owner_guid);
		$wire['owner']['guid'] = $owner->guid;
		$wire['owner']['name'] = $owner->name;
		$wire['owner']['avatar_url'] = get_entity_icon_url($owner,'small');
			
		$wire['time_created'] = (int)$single->time_created;
		$wire['description'] = $single->description;
		$return[] = $wire;
	} 
} else {
		$msg = elgg_echo('thewire:noposts');
		throw new InvalidParameterException($msg);
	}
	
	return $return;
	} 
				
expose_function('wire.get_posts',
				"wire_get_posts",
				array(	'context' => array ('type' => 'string', 'required' => false, 'default' => 'all'),
						'limit' => array ('type' => 'int', 'required' => false),
						'offset' => array ('type' => 'int', 'required' => false),
						'username' => array ('type' => 'string', 'required' =>false),
					),
				"Read lates wire post",
				'GET',
				false,
				false);
				
				
/**
 * Web service for delete a wire post
 *
 * @param string $username username
 * @param string $wireid   GUID of wire post to delete
 *
 * @return bool
 */
function wire_delete($username, $wireid) {
	$user = get_user_by_username($username);
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	
	$thewire = get_entity($wireid);
	$return['success'] = false;
	if ($thewire->getSubtype() == "thewire" && $thewire->canEdit($user->guid)) {
		$children = elgg_get_entities_from_relationship(array(
			'relationship' => 'parent',
			'relationship_guid' => $wireid,
			'inverse_relationship' => true,
		));
		if ($children) {
			foreach ($children as $child) {
				$child->reply = false;
			}
		}
		$rowsaffected = $thewire->delete();
		if ($rowsaffected > 0) {
			$return['success'] = true;
			$return['message'] = elgg_echo("thewire:deleted");
		} else {
			$return['message'] = elgg_echo("thewire:notdeleted");
		}
	}
	else {
		$return['message'] = elgg_echo("thewire:notdeleted");
	}
	return $return;
} 
				
expose_function('wire.delete_posts',
				"wire_delete",
				array('username' => array ('type' => 'string'),
						'wireid' => array ('type' => 'int'),
					),
				"Delete a wire post",
				'POST',
				true,
				false);