<?php
/**
 * Minds Social Integration
 *
 * @package Minds
 * @author Mark Harding
 *
 */

function minds_social_init(){
										
	elgg_extend_view('css/elgg','minds_social/css');
	
	elgg_register_class('TwitterOAuth', elgg_get_plugins_path() . "minds_social/vendors/twitteroauth/twitterOAuth.php");
	
	//register the facebook library
	elgg_register_library('facebook', elgg_get_plugins_path() . 'minds_social/lib/facebook/facebook.php');
	elgg_register_library('minds:facebook', elgg_get_plugins_path() . 'minds_social/lib/facebook/fbmain.php');
	elgg_register_class('TwitterOAuth', elgg_get_plugins_path() . "minds_social/vendors/twitteroauth/twitterOAuth.php");
	elgg_register_library('minds:twitter', elgg_get_plugins_path() . "minds_social/lib/twitter/twitter.php");
	
	elgg_load_library('minds:facebook');
	elgg_load_library('minds:twitter');
	
	elgg_register_page_handler('social', 'minds_social_page_handler');
	
	elgg_register_event_handler('create','object','minds_social_action');
	
	elgg_extend_view('forms/login', 'minds_social/login');
		
 	elgg_extend_view('page/elements/head','minds_social/meta');
	
	elgg_extend_view('object/elements/full', 'minds_social/social_footer');
	
}

/**
 * @param array $page
 */
function minds_social_page_handler($page)
{
	global $CONFIG;

	if (!isset($page[1])) {
		forward();
	}
	$_GET['session'] = $CONFIG->input['session'];
	
	if($page[0] == 'fb'){
		switch ($page[1]) {
			case 'auth':
				minds_social_facebook_auth();
				break;
			case 'login':
				minds_social_facebook_login();
			default:
				forward();
				break;
		}
	} elseif($page[0] == 'twitter'){
		switch ($page[1]) {
			case 'auth':
				minds_social_twitter_auth();
				break;
			case 'forward':
				minds_social_twitter_forward();
				break;
			case 'login':
				minds_social_twitter_login();
				break;
			default:
				forward();
				break;
		}
	}
}

function minds_social_action($event, $object_type, $object){
	if(elgg_is_logged_in()){
	
	$facebook = minds_social_facebook_init();
	
	$user = elgg_get_logged_in_user_entity();
	
	//twitter
	$consumer = minds_social_twitter_init();
	$access_key = elgg_get_plugin_user_setting('minds_social_twitter_access_key', $user->getGuid());
	$access_secret = elgg_get_plugin_user_setting('minds_social_twitter_access_secret', $user->getGuid());
	//facebook
	$fb_access_token = elgg_get_plugin_user_setting('minds_social_facebook_access_token', $user->getGuid());
	
	//send a wirepost
	if(get_subtype_from_id($object->subtype) == 'wallpost' && $object->owner_guid == $object->to_guid){
		
		//post to facebook.
		try{
			$facebook->api('/me/feed', 'POST', array('message'=>$object->message, 'access_token' => $fb_access_token));
		} catch(Exception $e){
		}
		
		//post to twitter
		$desc = $object->message;
		if(strlen($desc) > 140){
		$desc = substr($desc, 0, 100) . '... ' . $object->getURL();
		}
		$api = new TwitterOAuth($consumer['key'], $consumer['secret'], $access_key, $access_secret);
		$api->post('statuses/update', array('status' => $desc));
	}
	
	//say blog has been written
	if(get_subtype_from_id($object->subtype) == 'blog'){
		
		//post to facebook.
		try{
			$facebook->api('/me/news.publishes', 'POST', array('property_name'=>$object->getURL(), 'article' => $object->getURL(),'access_token' => $fb_access_token));
		} catch(Exception $e){
		}
		
		//post to twitter
		$api = new TwitterOAuth($consumer['key'], $consumer['secret'], $access_key, $access_secret);
		$api->post('statuses/update', array('status' => 'I published a new blog on Minds. ' . $object->getURL()));
	}
	
	}
	
	return true;
}

function minds_set_metatags($name, $content){
	
	$strip = strip_tags($content);
	set_input($name, str_replace('"', "", $strip));
	
	return;
	
}

function minds_get_fbimage($description) {
  
  global $post, $posts;
  $fbimage = '';
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i',
  $description, $matches);
  $fbimage = $matches [1] [0];
 
  if(empty($fbimage)) {
    $fbimage = elgg_get_site_url() . 'mod/minds/graphics/minds_logo.png';
  }
  return $fbimage;
}

elgg_register_event_handler('init','system','minds_social_init');		

?>
