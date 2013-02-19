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
function minds_ws_login($username, $password, $client_id, $request_uri){
	if (true === elgg_authenticate($username, $password)) {
		// Get our custom storage object
	    $storage = new ElggOAuth2DataStore();
	
	    // Create a server instance
	    $server = new OAuth2_Server($storage);
	
	    // Client entities are private so ignore access during the lookup
	    $access = elgg_get_ignore_access();
	    elgg_set_ignore_access(true);
		
		$server->addGrantType(new OAuth2_GrantType_UserCredentials($storage));
		
	    $server->handleGrantRequest(OAuth2_Request::createFromGlobals())->send();
	
	    elgg_set_ignore_access($access);
	    
	    return $server;
	}
}
expose_function('minds.login',
				"minds_ws_login",
				array(	'username' => array ('type' => 'string', 'required' => true),
						'password' => array ('type' => 'string', 'required' => true),
						'client_id' => array ('type' => 'string', 'required' => true),
						'request_uri' => array ('type' => 'string', 'required' => true),
					),
				"Authenticate a user",
				'POST',
				false,
				false);
