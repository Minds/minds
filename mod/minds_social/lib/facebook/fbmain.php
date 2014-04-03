<?php

elgg_load_library('facebook');

/* Initialize the facebook object
 */
function minds_social_facebook_init(){
	$facebook = new Facebook(array(
	  'appId'  => elgg_get_plugin_setting('facebook_appId', 'minds_social') ?: '184865748231073',
	  'secret' => elgg_get_plugin_setting('facebook_secret', 'minds_social') ?: '26d5c6cd2ae8945e19b4b1bce2d9df9d',
	));
	
	return $facebook;
}

/* Begin the authentication process for facebook
 * Links a facebook account to a minds account
 */
function minds_social_facebook_auth($display = 'normal'){
	$facebook = minds_social_facebook_init();
	
	if (!$session['_fb'] = $facebook->getUser()) {
		forward(REFERER);
	}
	
	$user = elgg_get_logged_in_user_entity();
	
	if(!$user){
		forward();
	}
	
	
//	elgg_unset_plugin_user_setting('minds_social_facebook_uid', $user->getGUID());
//	elgg_unset_plugin_user_setting('minds_social_facebook_access_token', $user->getGUID());
	
	
	//get our access token 
	$access_token = $facebook->getAccessToken();

	$db = new DatabaseCall('user_index_to_guid');
	$db->insert('fb:'.$session['_fb'], array( $user->getGUID() => time()));

	// register user's access tokens
	elgg_set_plugin_user_setting('minds_social_facebook_uid', $session['_fb']);
	elgg_set_plugin_user_setting('minds_social_facebook_access_token', $access_token);
		
	system_message(elgg_echo('minds:social:facebook:authsuccess'));
		
	if($display == 'popup'){
		echo '<script>window.close();</script>';
		exit;
	} else {
		forward(REFERER);
	}
	
}
/**
 * Begin the signin process for facebook
 */
function minds_social_facebook_login(){
	global $SESSION;
	header("X-No-Client-Cache: 0", true);
	$facebook = minds_social_facebook_init();

	if(elgg_is_logged_in()){
		forward();
	}
	
	if (!$session['_fb'] = $facebook->getUser()){
		$return_url = elgg_get_site_url() . 'social/fb/login';
		forward($facebook->getLoginURL(array(
				'redirect_uri' => $return_url,
				'canvas' => 1,
				'scope' => 'publish_stream,email, offline_access',
				'ext_perm' =>  'offline_access',)));
		/*if($_SESSION['fb_referrer']){
			forward($_SESSION['fb_referrer']);
		} else {
			forward('login');
		}*/
	}
	// attempt to find user and log them in.
	// else, create a new user.
	$fb_uid = is_array($session['_fb']) ? $session['_fb']['uid'] : $session['_fb'];
	//check if there is a guid relating to the users fb_id
	$guid = get_user_index_to_guid('fb:'.$fb_uid);
	
	if($guid){
		$user = get_entity($guid, 'user');
	}

	if ($user){
		if(login($user)){
			system_message(elgg_echo('facebook_connect:login:success'));
			
			if(empty($user->email)) {
				$data = $facebook->api('/me');
				$email= $data['email'];
				$user = get_entity($users->guid);
				$user->email = $email;
				$user->save();
			}

			//we need to update the users access token so that we can post to their facebook walls
			//get our access token
       			 $access_token = $facebook->getAccessToken();
        		elgg_set_plugin_user_setting('minds_social_facebook_access_token', $access_token);

			forward();	
		
		} else {
			system_message(elgg_echo('facebook_connect:login:error'));
		}
	} else {
		// need facebook account credentials
		$data = $facebook->api('/me');     
		$email= $data['email'];
		
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
				elgg_clear_sticky_form('register');
	
				$new_user = get_entity($guid);
				
				//get our access token 
				$access_token = $facebook->getAccessToken();
			
				// register user's access tokens
				elgg_set_plugin_user_setting('minds_social_facebook_uid',  is_array($session['_fb']) ? $session['_fb']['uid'] : $session['_fb'],$guid);
				elgg_set_plugin_user_setting('minds_social_facebook_access_token', $access_token, $guid);
				
				//trigger the validator plugins
				$params = array(
					'user' => $new_user,
					'password' => $password,
					'friend_guid' => $friend_guid,
					'invitecode' => $invitecode
				);
			
				$db = new DatabaseCall('user_index_to_guid');
				$db->insert('fb:'.$fb_uid, array($guid => time()));//move this into the user class
	
				//Automatically subscribe user to the Minds Channel
				minds_subscribe_default(null,null,null, array('user'=>$new_user));
				login($new_user);
				/*if($_SESSION['fb_referrer']){
					forward($_SESSION['fb_referrer']);
				}*/
				forward('register/orientation');
			}	
		}else{
			try {
				if(login($users[0])){
					$access_token = $facebook->getAccessToken();                        
               	 			elgg_set_plugin_user_setting('minds_social_facebook_access_token', $access_token);
					forward('news');			
	
				/*	if($_SESSION['fb_referrer']){
                        		//forward($_SESSION['fb_referrer']);
                			} else {
                        		forward('news');
                			}*/
				}
			} catch (LoginException $e) {
				register_error($e->getMessage());
				/*if($_SESSION['fb_referrer']){
                 	      		 forward($_SESSION['fb_referrer']);
                		} else {
                       			 forward(REFERRER);
                		}*/
			}
		}
	}
}

function minds_social_facebook_remove(){
	$user = elgg_get_logged_in_user_entity();
	elgg_unset_plugin_user_setting('minds_social_facebook_uid', $user->getGUID());
	elgg_unset_plugin_user_setting('minds_social_facebook_access_token', $user->getGUID());
	forward('settings/plugins/'.$user->username);
	return true;
}
