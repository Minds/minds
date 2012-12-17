<?php

elgg_load_library('facebook');

/* Initialize the facebook object
 */
function minds_social_facebook_init(){
	$facebook = new Facebook(array(
	  'appId'  => '184865748231073',
	  'secret' => '26d5c6cd2ae8945e19b4b1bce2d9df9d',
	));
	
	return $facebook;
}

/* Begin the authentication process for facebook
 * Links a facebook account to a minds account
 */
function minds_social_facebook_auth(){
	$facebook = minds_social_facebook_init();
	
	if (!$session = $facebook->getUser()) {
		forward(REFERER);
	}
	
	$user = elgg_get_logged_in_user_entity();
	
	if(!$user){
		forward();
	}
	
	
	elgg_unset_plugin_user_setting('minds_social_facebook_uid', $user->getGUID());
	elgg_unset_plugin_user_setting('minds_social_facebook_access_token', $user->getGUID());
	

	//the info for that user doesnt match our facebook info so we update or create the info
	$data = $facebook->api('/me');
	
	//get our access token 
	$access_token = $facebook->getAccessToken();

	// register user's access tokens
	elgg_set_plugin_user_setting('minds_social_facebook_uid', $session);
	elgg_set_plugin_user_setting('minds_social_facebook_access_token', $access_token);
		
	system_message(elgg_echo('minds:social:facebook:authsuccess'));
		
	forward(REFERER);
	
}
/**
 * Begin the signin process for facebook
 */
function minds_social_facebook_login(){
	$facebook = minds_social_facebook_init();

	if (!$session = $facebook->getUser()){		
		$return_url = elgg_get_site_url() . 'social/fb/login';
		forward($facebook->getLoginURL(array(
				'redirect_uri' => $return_url,
				'canvas' => 1,
				'scope' => 'publish_stream,email, offline_access',
				'ext_perm' =>  'offline_access',)));
		if($_SESSION['fb_referrer']){
			forward($_SESSION['fb_referrer']);
		} else {
			forward('login');
		}
	}

	// attempt to find user and log them in.
	// else, create a new user.
	$options = array(
		'type' => 'user',
		'plugin_user_setting_name_value_pairs' => array(
			'minds_social_facebook_uid' => is_array($session) ? $session['uid'] : $session,
			'minds_social_facebook_access_token' => $session['access_token'],
		),
		'plugin_user_setting_name_value_pairs_operator' => 'OR',
		'limit' => 0
	);

	$users = elgg_get_entities_from_plugin_user_settings($options);	
	
	if ($users){
		if (count($users) == 1 && login($users[0])){
			system_message(elgg_echo('facebook_connect:login:success'));
			elgg_set_plugin_user_setting('access_token', $session['access_token'], $users[0]->guid);
			
			if(empty($users[0]->email)) {
				$data = $facebook->api('/me');
				$email= $data['email'];
				$user = get_entity($users[0]->guid);
				$user->email = $email;
				$user->save();
			}

			//we need to update the users access token so that we can post to their facebook walls
			//get our access token
       			 $access_token = $facebook->getAccessToken();
        		elgg_set_plugin_user_setting('minds_social_facebook_access_token', $access_token);
			
		} else {
			system_message(elgg_echo('facebook_connect:login:error'));
		}
	} else {
		// need facebook account credentials
		$data = $facebook->api('/me');     
		$email= $data['email'];
		
		$users= get_user_by_email($email);
	
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
				elgg_set_plugin_user_setting('minds_social_facebook_uid', $session);
				elgg_set_plugin_user_setting('minds_social_facebook_access_token', $access_token);
				
				//trigger the validator plugins
				$params = array(
					'user' => $new_user,
					'password' => $password,
					'friend_guid' => $friend_guid,
					'invitecode' => $invitecode
				);
				
				// @todo should registration be allowed no matter what the plugins return?
				/*if (!elgg_trigger_plugin_hook('register', 'user', $params, TRUE)) {
					$new_user->delete();
					// @todo this is a generic messages. We could have plugins
					// throw a RegistrationException, but that is very odd
					// for the plugin hooks system.
					throw new RegistrationException(elgg_echo('registerbad'));
				}*/
				login($new_user);
				if($_SESSION['fb_referrer']){
					forward($_SESSION['fb_referrer']);
				}
			}	
		}else{
			try {
				if(login($users[0])){
					$access_token = $facebook->getAccessToken();                        
               	 	elgg_set_plugin_user_setting('minds_social_facebook_access_token', $access_token);
					
					if($_SESSION['fb_referrer']){
                        		//forward($_SESSION['fb_referrer']);
                	} else {
                        		forward('news');
                	}
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

