<?php
/**
 * Elgg Webservices plugin 
 * 
 * @package Webservice
 * @author Saket Saurabh
 *
 */

/**
 * Web service to get profile labels
 *
 * @return string $profile_labels Array of profile labels
 */
function user_get_profile_fields() {	
	$user_fields = elgg_get_config('profile_fields');
	foreach ($user_fields as $key => $type) {
		$profile_labels[$key]['label'] = elgg_echo('profile:'.$key);
		$profile_labels[$key]['type'] = $type;
	}
	return $profile_labels;
}
	
expose_function('user.get_profile_fields',
				"user_get_profile_fields",
				array(),
				"Get user profile labels",
				'GET',
				false,
				false);

/**
 * Web service to get profile information
 *
 * @param string $username username to get profile information
 *
 * @return string $profile_info Array containin 'core', 'profile_fields' and 'avatar_url'
 */
function user_get_profile($username) {
	//if $username is not provided then try and get the loggedin user
	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
		if(!$user){
			$user = get_entity($username);
		}
	}
	
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	
	$user_fields = elgg_get_config('profile_fields');
	
	foreach ($user_fields as $key => $type) {
		if($user->$key){
			$profile_fields[$key]['label'] = elgg_echo('profile:'.$key);
			$profile_fields[$key]['type'] = $type;
			if(is_array($user->$key)){
			$profile_fields[$key]['value'] = $user->$key;

			} else {
			$profile_fields[$key]['value'] = strip_tags($user->$key);
			}
		}
	}
	
	$core['guid'] = $user->guid;
	$core['name'] = $user->name;
	$core['username'] = $user->username;
	if($user->canEdit())
		$core['email'] = $user->email;
	
	$profile_info['core'] = $core;
	$profile_info['profile_fields'] = $profile_fields;
	$profile_info['avatar_url'] = get_entity_icon_url($user,'medium');
	return $profile_info;
}

expose_function('user.get_profile',
				"user_get_profile",
				array('username' => array ('type' => 'string', 'required' => false)
					),
				"Get user profile information",
				'GET',
				false,
				false);
/**
 * Web service to update profile information
 *
 * @param string $username username to update profile information
 *
 * @return bool 
 */
function user_save_profile($username, $profile) {
	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
	}
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	$owner = get_entity($user->guid);
	$profile_fields = elgg_get_config('profile_fields');
	foreach ($profile_fields as $shortname => $valuetype) {
		$value = $profile[$shortname];
		$value = html_entity_decode($value, ENT_COMPAT, 'UTF-8');

		if ($valuetype != 'longtext' && elgg_strlen($value) > 250) {
			$error = elgg_echo('profile:field_too_long', array(elgg_echo("profile:{$shortname}")));
			return $error;
		}

		if ($valuetype == 'tags') {
			$value = string_to_tag_array($value);
		}
		$input[$shortname] = $value;
	}
	
	$name = strip_tags($profile['name']);
	if ($name) {
		if (elgg_strlen($name) > 50) {
			return elgg_echo('user:name:fail');
		} elseif ($owner->name != $name) {
			$owner->name = $name;
			return $owner->save();
			if (!$owner->save()) {
				return elgg_echo('user:name:fail');
			}
		}
	}
	
	if (sizeof($input) > 0) {
		foreach ($input as $shortname => $value) {
			$options = array(
				'guid' => $owner->guid,
				'metadata_name' => $shortname
			);
			elgg_delete_metadata($options);
			
			if (isset($accesslevel[$shortname])) {
				$access_id = (int) $accesslevel[$shortname];
			} else {
				// this should never be executed since the access level should always be set
				$access_id = ACCESS_DEFAULT;
			}
			
			if (is_array($value)) {
				$i = 0;
				foreach ($value as $interval) {
					$i++;
					$multiple = ($i > 1) ? TRUE : FALSE;
					create_metadata($owner->guid, $shortname, $interval, 'text', $owner->guid, $access_id, $multiple);
				}
				
			} else {
				create_metadata($owner->guid, $shortname, $value, 'text', $owner->guid, $access_id);
			}
		}
		
	}
	
	return "Success";
}
	
expose_function('user.save_profile',
				"user_save_profile",
				array('username' => array ('type' => 'string'),
					 'profile' => array ('type' => 'array'),
					),
				"Get user profile information with username",
				'POST',
				true,
				false);

/**
 * Web service to get all users registered with an email ID
 *
 * @param string $email Email ID to check for
 *
 * @return string $foundusers Array of usernames registered with this email ID
 */
function user_get_user_by_email($email) {
	if (!validate_email_address($email)) {
		throw new RegistrationException(elgg_echo('registration:notemail'));
	}

	$user = get_user_by_email($email);
	if (!$user) {
		throw new InvalidParameterException('registration:emailnotvalid');
	}
	foreach ($user as $key => $singleuser) {
		$foundusers[$key] = $singleuser->username;
	}
	return $foundusers;
}

expose_function('user.get_user_by_email',
				"user_get_user_by_email",
				array('email' => array ('type' => 'string'),
					),
				"Get Username by email",
				'GET',
				false,
				false);

/**
 * Web service to check availability of username
 *
 * @param string $username Username to check for availaility 
 *
 * @return bool
 */           
function user_check_username_availability($username) {
	$user = get_user_by_username($username);
	if (!$user) {
		return true;
	} else {
		return false;
	}
}

expose_function('user.check_username_availability',
				"user_check_username_availability",
				array('username' => array ('type' => 'string'),
					),
				"Get Username by email",
				'GET',
				false,
				false);

/**
 * Web service to register user
 *
 * @param string $name     Display name 
 * @param string $email    Email ID 
 * @param string $username Username
 * @param string $password Password 
 *
 * @return bool
 */           
function user_register($name, $email, $username, $password) {
	$user = get_user_by_username($username);
	if (!$user) {
		$return['success'] = true;
		$return['guid'] = register_user($username, $password, $name, $email);
	} else {
		$return['success'] = false;
		$return['message'] = elgg_echo('registration:userexists');
	}
	return $return;
}

expose_function('user.register',
				"user_register",
				array('name' => array ('type' => 'string'),
						'email' => array ('type' => 'string'),
						'username' => array ('type' => 'string'),
						'password' => array ('type' => 'string'),
					),
				"Register user",
				'GET',
				false,
				false);

/**
 * Web service to add as friend
 *
 * @param string $username Username
 * @param string $friend Username to be added as friend
 *
 * @return bool
 */           
function user_friend_add($friend, $username) {
	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
	}
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	
	$friend_user = get_user_by_username($friend);
	if (!$friend_user) {
		$msg = elgg_echo("friends:add:failure", array($friend_user->name));
	 	throw new InvalidParameterException($msg);
	}
	
	if($friend_user->isFriendOf($user->guid)) {
		$msg = elgg_echo('friends:alreadyadded', array($friend_user->name));
	 	throw new InvalidParameterException($msg);
	}
	
	
	if ($user->addFriend($friend_user->guid)) {
		// add to river
		add_to_river('river/relationship/friend/create', 'friend', $user->guid, $friend_user->guid);
		$return['success'] = true;
		$return['message'] = elgg_echo('friends:add:successful' , array($friend_user->name));
	} else {
		$msg = elgg_echo("friends:add:failure", array($friend_user->name));
	 	throw new InvalidParameterException($msg);
	}
	return $return;
}

expose_function('user.friend.add',
				"user_friend_add",
				array(
						'friend' => array ('type' => 'string'),
						'username' => array ('type' => 'string', 'required' =>false),
					),
				"Add a user as friend",
				'POST',
				true,
				false);	
				

/**
 * Web service to remove friend
 *
 * @param string $username Username
 * @param string $friend Username to be removed from friend
 *
 * @return bool
 */           
function user_friend_remove($friend,$username) {
	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
	}
	if (!$user) {
	 	throw new InvalidParameterException('registration:usernamenotvalid');
	}
	
	$friend_user = get_user_by_username($friend);
	if (!$friend_user) {
		$msg = elgg_echo("friends:remove:failure", array($friend_user->name));
	 	throw new InvalidParameterException($msg);
	}
	
	if(!$friend_user->isFriendOf($user->guid)) {
		$msg = elgg_echo("friends:remove:notfriend", array($friend_user->name));
	 	throw new InvalidParameterException($msg);
	}
	
	
		if ($user->removeFriend($friend_user->guid)) {
		
		$return['message'] = elgg_echo("friends:remove:successful", array($friend->name));
		$return['success'] = true;
	} else {
		$msg = elgg_echo("friends:add:failure", array($friend_user->name));
	 	throw new InvalidParameterException($msg);
	}
	return $return;
}

expose_function('user.friend.remove',
				"user_friend_remove",
				array(
						'friend' => array ('type' => 'string'),
						'username' => array ('type' => 'string', 'required' => false),
					),
				"Remove friend",
				'GET',
				true,
				true);				
				
/**
 * Web service to get friends of a user
 *
 * @param string $username Username
 * @param string $limit    Number of users to return
 * @param string $offset   Indexing offset, if any
 *
 * @return array
 */           
function user_get_friends($username, $limit = 10, $offset = 0) {
	if($username){
		$user = get_user_by_username($username);
	} else {
		$user = get_loggedin_user();
	}
	if (!$user) {
		throw new InvalidParameterException(elgg_echo('registration:usernamenotvalid'));
	}
	$friends = get_user_friends($user->guid, '' , $limit, $offset);
	
	if($friends){
	foreach($friends as $single) {
		$friend['guid'] = $single->guid;
		$friend['username'] = $single->username;
		$friend['name'] = $single->name;
		$friend['avatar_url'] = get_entity_icon_url($single,'small');
		$return[] = $friend;
	}
	} else {
		$msg = elgg_echo('friends:none');
		throw new InvalidParameterException($msg);
	}
	return $return;
}

expose_function('user.friend.get_friends',
				"user_get_friends",
				array('username' => array ('type' => 'string', 'required' => false),
						'limit' => array ('type' => 'int', 'required' => false),
						'offset' => array ('type' => 'int', 'required' => false),
					),
				"Register user",
				'GET',
				false,
				false);	
				
/**
 * Web service to obtains the people who have made a given user a friend
 *
 * @param string $username Username
 * @param string $limit    Number of users to return
 * @param string $offset   Indexing offset, if any
 *
 * @return array
 */           
function user_get_friends_of($username, $limit = 10, $offset = 0) {
	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
	}
	if (!$user) {
		throw new InvalidParameterException(elgg_echo('registration:usernamenotvalid'));
	}
	$friends = get_user_friends_of($user->guid, '' , $limit, $offset);
	
	$success = false;
	foreach($friends as $friend) {
		$return['guid'] = $friend->guid;
		$return['username'] = $friend->username;
		$return['name'] = $friend->name;
		$return['avatar_url'] = get_entity_icon_url($friend,'small');
		$success = true;
	}
	
	if(!$success) {
		$return['error']['message'] = elgg_echo('friends:none');
	}
	return $return;
}

expose_function('user.friend.get_friends_of',
				"user_get_friends_of",
				array('username' => array ('type' => 'string', 'required' => true),
						'limit' => array ('type' => 'int', 'required' => false),
						'offset' => array ('type' => 'int', 'required' => false),
					),
				"Register user",
				'GET',
				false,
				false);	
				

/**
 * Web service to retrieve the messageboard for a user
 *
 * @param string $username Username
 * @param string $limit    Number of users to return
 * @param string $offset   Indexing offset, if any
 *
 * @return array
 */    				
function user_get_messageboard($limit = 10, $offset = 0, $username){
	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
		if (!$user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}
	
$options = array(
	'annotations_name' => 'messageboard',
	'guid' => $user->guid,
	'limit' => $limit,
	'pagination' => false,
	'reverse_order_by' => true,
);

	$messageboard = elgg_get_annotations($options);
	
	if($messageboard){
	foreach($messageboard as $single){
		$post['id'] = $single->id;
		$post['description'] = $single->value;
		
		$owner = get_entity($single->owner_guid);
		$post['owner']['guid'] = $owner->guid;
		$post['owner']['name'] = $owner->name;
		$post['owner']['username'] = $owner->username;
		$post['owner']['avatar_url'] = get_entity_icon_url($owner,'small');
		
		$post['time_created'] = (int)$single->time_created;
		$return[] = $post;
	}
} else {
		$msg = elgg_echo('messageboard:none');
		throw new InvalidParameterException($msg);
	}
 	return $return;
}
expose_function('user.get_messageboard',
				"user_get_messageboard",
				array(
						'limit' => array ('type' => 'int', 'required' => false, 'default' => 10),
						'offset' => array ('type' => 'int', 'required' => false, 'default' => 0),
						'username' => array ('type' => 'string', 'required' => false),
					),
				"Get a users messageboard",
				'GET',
				false,
				false);	
/**
 * Web service to post to a messageboard
 *
 * @param string $text
 * @param string $to - username
 * @param string $from - username
 *
 * @return array
 */    				
function user_post_messageboard($text, $to, $from){
	if(!$to){
		$to_user = get_loggedin_user();
	} else {
		$to_user = get_user_by_username($to);
		if (!$to_user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}
	if(!$from){
		$from_user = get_loggedin_user();
	} else {
		$from_user = get_user_by_username($from);
		if (!$from_user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}
	
	$result = messageboard_add($from_user, $to_user, $text, 2);

	if($result){
		$return['success']['message'] = elgg_echo('messageboard:posted');
	} else {
		$return['error']['message'] = elgg_echo('messageboard:failure');
	}
	return $return;
}
expose_function('user.post_messageboard',
				"user_post_messageboard",
				array(
						'text' => array ('type' => 'string'),
						'to' => array ('type' => 'string', 'required' => false),
						'from' => array ('type' => 'string', 'required' => false),
					),
				"Post a messageboard post",
				'POST',
				true,
				true);	
