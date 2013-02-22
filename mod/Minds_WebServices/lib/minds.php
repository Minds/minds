<?php
/**
 * Elgg Webservices plugin 
 * Minds specific
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
 
 /**
 * Web service to remind an link from the Open API
 *
 * @param string $url
 * @param string $title (optional)
 * @param string $descriptipon (optional)
 * @param string $username (optional) the username of the user default loggedin user
 *
 * @return bool true/false
 */
function minds_service_remind($url, $title, $description, $username) {	
	if(!$username) {
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
		if (!$user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}

	$remind = new ElggObject();
	$remind->subtype = 'remind';
	$remind->owner_guid = elgg_get_logged_in_user_guid();
	$remind->access_id = get_default_access();
	$remind->method = 'API';
	//$remind->site = $site; @MH - is there are way to grab the site the user is on??
	$remind->title = $title;
	$remind->description = $description;
	$remind->href = $url;
	
	$guid = $remind->save();
	add_to_river('river/remind/api', 'remind', elgg_get_logged_in_user_guid(), $guid);
	//add_entity_relationship($guid, 'remind', elgg_get_logged_in_user_guid()); 

	return $guid;
}

expose_function('remind',
				"minds_service_remind",
				array(
						'url' => array ('type' => 'string', 'required' => true),
						'title' => array ('type' => 'string', 'required' => true),//soon to be optional once we implement scraping of some sort
						'description' => array ('type' => 'string', 'required' => true),//soon to be optional once we implement scraping of some sort
					  	'username' => array ('type' => 'string', 'required' => false),
					),
				"Perform a remind action from another site",
				'GET',
				true,
				true);
/**
 * Web service to check the authentication of a user
 */
function minds_service_checkauth(){
	return true;
}
expose_function('checkAuth',
				"minds_service_checkAuth",
				array(
					),
				"Check if a user is authenticated",
				'POST',
				true,
				true);

/** 
 * Web services to begin the OAuth2.0 auto login
 */
function minds_ws_login($username, $password){
	if (true === elgg_authenticate($username, $password)) {
		// Get our custom storage object
	    $token = create_user_token($username, 30);
		if ($token) {
			return $token;
		}
	}
	throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
}
expose_function('minds.login',
				"minds_ws_login",
				array(	'username' => array ('type' => 'string', 'required' => true),
						'password' => array ('type' => 'string', 'required' => true),
					),
				"Authenticate a user",
				'POST',
				false,
				false);
/** 
 * Web service to allow login via facebook for mobile apps
 * @todo write the main fb login into functions so we aren't reproducing the same code here
 */
function minds_social_ws_fb_login($fb_access_token, $email, $uid){
	//grab the info about the user
	$facebook = minds_social_facebook_init();
	$data = $facebook->api('/me', 'GET', array('access_token'=>$fb_access_token));    
	if($email != $data['email']){
		return false; //this user does not match what we asked for.
	}
	if($uid != $data['id']){
		return false; //this user does not match what we asked for. 
	}
	//check if the user has logged in to minds with facebook before
	$options = array(
		'type' => 'user',
		'plugin_user_setting_name_value_pairs' => array(
			'minds_social_facebook_uid' => $data['id'],
			'minds_social_facebook_access_token' => $fb_access_token,
		),
		'plugin_user_setting_name_value_pairs_operator' => 'OR',
		'limit' => 0
	);
	$users = elgg_get_entities_from_plugin_user_settings($options);	
	var_dump($users);
	if ($users){
		if (count($users) == 1){
			if(empty($users[0]->email)) {
				$email= $data['email'];
				$user = get_entity($users[0]->guid);
				$user->email = $email;
				$user->save();
			}
        	elgg_set_plugin_user_setting('minds_social_facebook_access_token', $fb_access_token, $users[0]->guid);
					
			$token = create_user_token($users[0]->username, 30);
			if ($token) {
				return $token;
			}		
		} else {
			system_message(elgg_echo('facebook_connect:login:error'));
		}
	}
	//try to register the user, if an email address for that user already exists then link the accounts
	$users = get_user_by_email($email);
	if(!$users){
		//try and get the facebook username, if not - use their name
		$username = $data->username;
		if(!$data->username){
			$username = str_replace(' ', '', strtolower($data['name']));
		}
		while (get_user_by_username($username)){
			$username = $username . '.' . rand(0,100);
		}
		$name = $data['name'];
		$password = generate_random_cleartext_password();
		$email = $data['email'];
		$guid = register_user($username, $password, $name, $email);
		if($guid) {
			$new_user = get_entity($guid);
			//get our access token 
			$access_token = $facebook->getAccessToken();
			
			// register user's access tokens
			elgg_set_plugin_user_setting('minds_social_facebook_uid', $uid, $new_user->guid);
			elgg_set_plugin_user_setting('minds_social_facebook_access_token', $fb_access_token, $new_user->guid);
				
			//trigger the validator plugins
			$params = array(
					'user' => $new_user,
					'password' => $password,
					'friend_guid' => $friend_guid,
					'invitecode' => $invitecode
				);
				
			//login($new_user);
			//give a short term token to the user so they can authorise the mobile apps
			$token = create_user_token($new_user->username, 30);
			if ($token) {
				return $token;
			}
		}	
	}else{
        elgg_set_plugin_user_setting('minds_social_facebook_access_token', $fb_access_token, $users[0]->guid);
		
		$token = create_user_token($users[0]->username, 30);
		if ($token) {
			return $token;
		}			
	}
}
expose_function('minds.social.fb.login',
				"minds_social_ws_fb_login",
				array(
						'fb_access_token' => array ('type' => 'string', 'required' => true),
						'email' => array ('type' => 'string', 'required' => true),
						'uid' => array ('type' => 'string', 'required' => true),
					),
				"Authenticate a facebook user from mobile apps",
				'POST',
				false, 
				false);
