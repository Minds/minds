<?php
/**
 * OAuth2::token
 */
namespace minds\plugin\oauth2\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\gatherings\entities;
use minds\plugin\gatherings\helpers;
use minds\plugin\oauth2\storage;
use OAuth2;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\UserCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\HttpFoundationBridge\Response as BridgeResponse;

class token extends core\page implements Interfaces\page{

    public $csrf = false;	
	
	public function get($pages){
		
        echo "Please use POST";

	}
	
	
	public function post($pages){
        header("Access-Control-Allow-Origin: *");
        error_log('hit the token page..');
        $storage = new storage();
        
         // create array of supported grant types
        $grantTypes = array(
            'authorization_code' => new AuthorizationCode($storage),
            'user_credentials'   => new UserCredentials($storage),
            'refresh_token'   => new RefreshToken($storage),
        );
        
        $config = array(
            'enforce_state' => true, 
            'allow_implicit' => true,
            'always_issue_new_refresh_token' => true
        );
        
        if($_REQUEST['grant_type'] == 'password')
            $config['access_lifetime'] = 3600 * 24 * 30 * 6;
        
        $server = new OAuth2\Server($storage, $config, $grantTypes);
	    $server->addResponseType(new \minds\plugin\oauth2\tokenResponse($storage, $storage, $config), 'token');
     error_log('hit me..'); 
        return $server->handleTokenRequest(OAuth2\Request::createFromGlobals(), new \minds\plugin\oauth2\response())->send();
        
	}
	

	public function put($pages){}
	

	public function delete($pages){}
	
}
