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
	elgg_register_event_handler('create','annotation','minds_social_action');
	
	elgg_extend_view('forms/login', 'minds_social/login');
		
 	elgg_extend_view('page/elements/head','minds_social/meta');
	
	elgg_extend_view('object/elements/full', 'minds_social/social_footer');
	
	/**** DISABlING AUTO FACEBOOK REG FOR THE MOMENT ***
	 if (stripos(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST), 'facebook')|| get_input('fb_source') || get_input('code')){
		$facebook = minds_social_facebook_init();
		global $CONFIG;
		$_GET['session'] = $CONFIG->input['session'];
		if(!$session = $facebook->getUser()){
			if(!elgg_is_logged_in()){
			$_SESSION['fb_referrer'] = $_SERVER['REQUEST_URI'];
			minds_social_facebook_login();
			}
		}
	}*/
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
	
	if($object_type == 'object'){
		//send a wirepost
		if(get_subtype_from_id($object->subtype) == 'wallpost' && $object->owner_guid == $object->to_guid){
			
			if($object->facebook == 'on'){
				//post to facebook.
				try{
					$facebook->api('/me/feed', 'POST', array('message'=>$object->message, 'access_token' => $fb_access_token));
				} catch(Exception $e){
				}
			}
			
			if($object->twitter == 'on'){
				//post to twitter
				$desc = $object->message;
				if(strlen($desc) > 140){
				$desc = substr($desc, 0, 100) . '... ' . $object->getURL();
				}
				$api = new TwitterOAuth($consumer['key'], $consumer['secret'], $access_key, $access_secret);
				$api->post('statuses/update', array('status' => $desc));
			}
		}
		
		//say blog has been written
		if(get_subtype_from_id($object->subtype) == 'blog'){
			
			//post to facebook.
			try{
				$facebook->api('/me/mindscom:write', 'POST', array('property_name'=>$object->getURL(), 'article' => $object->getURL(),'access_token' => $fb_access_token));
			} catch(Exception $e){
			}
			
			//post to twitter
			$api = new TwitterOAuth($consumer['key'], $consumer['secret'], $access_key, $access_secret);
			$api->post('statuses/update', array('status' => 'I wrote a new blog on Minds. ' . $object->getURL()));
		}
		
		//say video has been posted
		if($object->getSubtype() == 'kaltura_video'){
			
			//post to facebook.
			try{
				$kaltura_server = elgg_get_plugin_setting('kaltura_server_url',  'kaltura_video');
				$partnerId = elgg_get_plugin_setting('partner_id', 'kaltura_video');
				$widgetUi = elgg_get_plugin_setting('custom_kdp', 'kaltura_video');
				$video_location = $kaltura_server . '/index.php/kwidget/wid/_'.$partnerId.'/uiconf_id/' . $widgetUi . '/entry_id/'. $object->kaltura_video_id;
				$video_location_secure = str_replace('http://', 'https://', $video_location);	
				$facebook->api('/me/feed', 'POST', array('source' => $video_location,'access_token' => $fb_access_token));
			} catch(Exception $e){
			}
			
			//post to twitter
			$api = new TwitterOAuth($consumer['key'], $consumer['secret'], $access_key, $access_secret);
			$api->post('statuses/update', array('status' => 'I created new media on Minds. ' . $object->getURL()));
		}
		
		if($object->getSubtype() == 'image'){
			
			//post to facebook.
			try{
				$photo_source = file_get_contents($object->getIconURL('large'));
				$facebook->api('/me/feed', 'POST', array('source'=>$object->getIconURL('large'),'name'=>$object->getTitle(),'link'=> $object->getURL(),'access_token' => $fb_access_token));
			} catch(Exception $e){
			}
	
			//post to twitter
			$api = new TwitterOAuth($consumer['key'], $consumer['secret'], $access_key, $access_secret);
			$api->post('statuses/update', array('status' => 'I created new media on Minds. ' . $object->getURL()));
		}
	} elseif($object_type == 'annotation'){
		if($object->name == 'thumbs:up'){
			$entity = get_entity($object->entity_guid);
			try{
				$facebook->api('/me/og.likes', 'POST', array( 'object' => $entity->getURL(),'access_token' => $fb_access_token));
			} catch(Exception $e){
			}
		}
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
  return url_encode($fbimage);
}

elgg_register_event_handler('init','system','minds_social_init');		

?>
