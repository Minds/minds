<?php
/**
 * Twitter service handler
 * 
 * Unfortunately, most of twitters php sdks are pretty crap, so we need to do a lot of the authentication work ourselves. 
 */

namespace minds\plugin\social\services;

use Minds\Components;
use Minds\Core;
use TijsVerkoyen;

class twitter extends core\base{
	
	public function init(){

	}
	
	public function authorizeURL(){
		$tw = $this->tw();
		$token = $this->requestToken();
		$url = $tw::SECURE_API_URL . '/oauth/authenticate?oauth_token=' . $token['oauth_token'];
		return $url;
	}
	
	public function authorizeCallback(){
		$tw = $this->tw();
		$response = $tw->oAuthAccessToken($_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);
		
		\elgg_set_plugin_user_setting('twitter', 'enabled', core\session::getLoggedinUser()->guid, 'social');
		\elgg_set_plugin_user_setting('twitter_access_token', (string) $response['oauth_token'], core\session::getLoggedinUser()->guid, 'social');
		\elgg_set_plugin_user_setting('twitter_access_secret', (string) $response['oauth_token_secret'], core\session::getLoggedinUser()->guid, 'social');
		echo elgg_view('social/callback/twitter');
	}

	public function post($activity){
		$message = $activity['message'];
		if(strlen($message) > 140){
			$message = substr($message,0,80) . '...';
			$message .= elgg_get_site_url() . 'newsfeed/'.$activity['guid'];
		}
		$tw = $this->tw();
		$tw->setOAuthToken(\elgg_get_plugin_user_setting('twitter_access_token', core\session::getLoggedinUser()->guid, 'social'));
		$tw->setOAuthTokenSecret(\elgg_get_plugin_user_setting('twitter_access_secret', core\session::getLoggedinUser()->guid, 'social'));
		$tw->statusesUpdate($message);

	}
	
	public function tw(){
		return  new TijsVerkoyen\Twitter\Twitter(elgg_get_plugin_setting('twitter_api_key','social'), elgg_get_plugin_setting('twitter_api_secret','social'));
	}
	public function requestToken(){
		$tw = $this->tw();
		$response = $tw->oAuthRequestToken(elgg_get_site_url() . 'plugin/social/redirect/twitter');
		return $response;
	}
	
	public function call($endpoint, $method, $params){
		
	}
		
}
	
