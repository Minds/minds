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
function minds_service_remind($url, $message, $username) {	
	if(!$username) {
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
		if (!$user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}

	$wallpost = new WallPost();
	$wallpost->to_guid = $user->guid;
	$wallpost->owner_guid = $user->guid;
	$wallpost->message = $message;
	$wallpost->access_id = 2;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://iframely.com/iframely?uri=".urldecode($url)); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$output = curl_exec($ch); 
	curl_close($ch);   
	$meta = json_decode($output);

	$wallpost->meta_title = $meta->meta->title;
	$wallpost->meta_description = $meta->meta->description;
	$wallpost->meta_icon = $meta->links[0]->href;
	$wallpost->meta_url = $url;
	$guid = $wallpost->save(); 
	
	$options = array(
		'cc' => array($wallpost->to_guid),
		'subject_guid' => $wallpost->owner_guid,
		'body' => $wallpost->message . 'test',
		'view' => 'river/object/wall/create',
		'object_guid' => $wallpost->guid, //needed until we do some changes to the thumbs and comments plugins
		'attachment_guid' => $post_obj->attachment,
		'access_id' => $wallpost->access_id,
		
		'meta_title' => $wallpost->meta_title,
		'meta_description' => $wallpost->meta_description,
		'meta_icon' => $wallpost->meta_icon,
		'meta_url' => $wallpost->meta_url,
		);
	if($wallpost->access_id == ACCESS_PRIVATE)
		$options['timeline_override'] = array($wallpost->to_guid); //only post to the to_guid timeline..
		
	$river = new ElggRiverItem($options);
	if ($result = $river->save()) {
	    
	    // Remind has been saved, cache/increment count. TODO: Discuss whether this is the best way
	    $cacher = \minds\core\data\cache\factory::build();
	    
	    $key = "remind-".md5($url);
	    $count = (int)$cacher->get($key);
	    $cacher->set($key, $count++);
	    
	    return $result;
	}
	return false;
}

expose_function('remind',
				"minds_service_remind",
				array(
						'url' => array ('type' => 'string', 'required' => true),
						'message' =>array ('type' => 'string', 'default'=>'', 'required' => false),
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
	$uid = (int) $uid;
	//check if the user has logged in to minds with facebook before
	$options = array(
		'type' => 'user',
		'plugin_id' => 'minds_social',
		'plugin_user_setting_name_value_pairs' => array(
			'minds_social_facebook_uid' => $uid,
			'minds_social_facebook_access_token' => $fb_access_token,
		),
		'plugin_user_setting_name_value_pairs_operator' => 'OR',
		'limit' => 0
	);
	$users = elgg_get_entities_from_plugin_user_settings($options);	
	if ($users){
		if (count($users) == 1){
			if(empty($users[0]->email)) {
				$email= $data['email'];
				$user = get_entity($users[0]->guid);
				$user->email = $email;
				$user->save();
			}
        	elgg_set_plugin_user_setting('minds_social_facebook_access_token', $fb_access_token, $users[0]->guid,'minds_social');
					
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
			elgg_set_plugin_user_setting('minds_social_facebook_uid', $uid, $new_user->guid, 'minds_social');
			elgg_set_plugin_user_setting('minds_social_facebook_access_token', $fb_access_token, $new_user->guid, 'minds_social');
				
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
		elgg_set_plugin_user_setting('minds_social_facebook_uid', $uid, $users[0]->guid, 'minds_social');
        elgg_set_plugin_user_setting('minds_social_facebook_access_token', $fb_access_token, $users[0]->guid, 'minds_social');
		
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
				
function minds_get_site_menu(){
	global $CONFIG;
	elgg_trigger_event('pagesetup', 'system');
	$menu = elgg_view_menu('site',array('sort_by'=>'priority'));
	global $jsonexport;
	return $jsonexport;
}
expose_function('get.siteMenu',
				"minds_get_site_menu",
				array(),
				'Retrieve the site menu',
				'GET',
				false);
