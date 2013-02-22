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
 */
function minds_social_ws_fb_login($fb_access_token, $email, $uid){
	//grab the info about the user
	$data = $facebook->api('/me', 'POST', array('access_token'=>$fb_access_token));     
	$email= $data['email'];
	//check if this user has a minds account
	$users	= get_user_by_email($email);
	return var_dump($data);
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
