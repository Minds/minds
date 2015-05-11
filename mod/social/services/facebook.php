<?php
/**
 * Social 
 */

namespace minds\plugin\social\services;

use Minds\Components;
use Minds\Core;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\Entities\AccessToken;

class facebook extends core\base{
	
	protected $redirect_uri;
    public $access_token;

	public function init(){		
		FacebookSession::setDefaultApplication(elgg_get_plugin_setting('facebook_api_key','social'),elgg_get_plugin_setting('facebook_api_secret', 'social'));
        $this->redirect_uri = elgg_get_site_url() . 'plugin/social/redirect/facebook';
       if(!$this->access_token)
            $this->access_token = \elgg_get_plugin_user_setting('facebook_access_token', core\session::getLoggedinUser()->guid, 'social');
    }
	
	public function authorizeURL(){
				
		$helper = new FacebookRedirectLoginHelper($this->redirect_uri);
		return $loginUrl = $helper->getLoginUrl(array('publish_actions'));

	}
	
	public function authorizeCallback(){
		$helper = new FacebookRedirectLoginHelper($this->redirect_uri);
        try {
		  $session = $helper->getSessionFromRedirect();
		} catch(FacebookRequestException $e) {
		  // When Facebook returns an error
            error_log("FB ERROR:: " . $e->getMessage());
        } catch(\Exception $e) {
            // When validation fails or other local issues
            error_log("FB ERROR:: " . $e->getMessage());
		}

        error_log(print_r($session,true));

		if ($session) {
			$accessToken = $session->getAccessToken();
  			$at = $accessToken->extend();
			
			\elgg_set_plugin_user_setting('facebook', 'enabled', core\session::getLoggedinUser()->guid, 'social');
			\elgg_set_plugin_user_setting('facebook_access_token', (string) $at, core\session::getLoggedinUser()->guid, 'social');
			
			echo elgg_view('social/callback/facebook');
		}
	
	}

	/**
	 * Post to facebook from a minds activity post
	 * 
	 * @param array $activity
	 */
	public function post($activity){
        error_log("access_token == $this->access_token");    
        error_log(print_r($activity, true));
        
        $at = new AccessToken($this->access_token);
		$session = new FacebookSession($at);
		
		$data = array();
		if(isset($activity['message'])){
            $data['message'] = $activity['message'];
        }


		if(isset($activity['perma_url']) && $activity['perma_url'] != elgg_get_site_url() && isset($activity['thumbnail_src'])){
            $data['link'] = $activity['perma_url'];
            //$data['link'] = str_replace(parse_url($activity['perma_url'], PHP_URL_SCHEME), '', $activity['perma_url']);
        }
		
		if(isset($activity['thumbnail_src']) && $activity['thumbnail_src']){
            $data['picture'] = $activity['thumbnail_src'];
            $data['description'] = $activity['description'] ?: '@' . core\session::getLoggedinUser()->username;
        }
       error_log(print_r($data, true)); 

		try {
			$req = new FacebookRequest( $session, 'POST', '/me/feed', $data);
		    $response = $req->execute()->getGraphObject();
	       error_log("posted to fb");	
	//	    echo "Posted with id: " . $response->getProperty('id');
		
		  } catch(FacebookRequestException $e) {
		
	//		echo "Exception occured, code: " . $e->getCode();
      //      echo " with message: " . $e->getMessage();
            error_log("FB POST ERROR " .  $e->getCode());
			\elgg_set_plugin_user_setting('facebook', 'failed', core\session::getLoggedinUser()->guid, 'social');	
			error_log($e->getMessage());	
		  }   
	}
		
}
	
