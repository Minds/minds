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

    private $token;
    private $secret;

    public function __construct($params = array()){
        if(isset($params['access_token'])){
            list($this->token, $this->secret) = explode('&&',$params['access_token']);
        }
    }

	public function authorizeURL(){
		$tw = $this->tw();
		$token = $this->requestToken();
		$url = $tw::SECURE_API_URL . '/oauth/authenticate?oauth_token=' . $token['oauth_token'];
		return $url;
	}
	
	public function authorizeCallback(){
        if(core\session::getLoggedinUser()->guid && !isset($_REQUEST['client_id'])){
            $tw = $this->tw();
            $response = $tw->oAuthAccessToken($_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);
            
            \elgg_set_plugin_user_setting('twitter', 'enabled', core\session::getLoggedinUser()->guid, 'social');
            \elgg_set_plugin_user_setting('twitter_access_token', (string) $response['oauth_token'], core\session::getLoggedinUser()->guid, 'social');
            \elgg_set_plugin_user_setting('twitter_access_secret', (string) $response['oauth_token_secret'], core\session::getLoggedinUser()->guid, 'social');
            echo elgg_view('social/callback/twitter');
        } else {
            $tw = $this->tw();
            $response = $tw->oAuthAccessToken($_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);
            echo json_encode($response);
        }
        //echo json_encode($response);
    }

	public function post($activity){

        error_log($this->token);
        error_log($this->secret);

        $message = $activity['message'];
		if(strlen($message) > 115){
			$message = substr($message,0,105) . '...';
		}
        $message .= " " . $activity['perma_url']; 
        error_log("mesage is ".$message);
        if(!$message)
			return true;
		$tw = $this->tw();
   
        $tw->setOAuthToken($this->token ?: \elgg_get_plugin_user_setting('twitter_access_token', core\session::getLoggedinUser()->guid, 'social'));
		$tw->setOAuthTokenSecret($this->secret ?: \elgg_get_plugin_user_setting('twitter_access_secret', core\session::getLoggedinUser()->guid, 'social'));
		$tw->statusesUpdate($message);
        error_log("set API request to twitter");

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
	
