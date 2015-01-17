<?php
/**
 * OAuth2::authorize
 */
namespace minds\plugin\oauth2\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\oauth2\entities;
use minds\plugin\oauth2\storage;
use OAuth2;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\UserCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\HttpFoundationBridge\Response as BridgeResponse;

class authorize extends core\page implements interfaces\page{
	
	
	public function get($pages){
	    
        if(isset($pages[0])){
            
            $storage = new storage();
            $server = new OAuth2\Server($storage);
            $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

            return $server->handleAuthorizeRequest(OAuth2\Request::createFromGlobals(), new \minds\plugin\oauth2\response(), true)->send();
            
        }
	    
		$storage = new storage();
        $server = new OAuth2\Server($storage);
        
        // validate the authorize request.  if it is invalid, redirect back to the client with the errors in tow
        if (!$server->validateAuthorizeRequest(OAuth2\Request::createFromGlobals(), new \minds\plugin\oauth2\response())) {
            return $server->getResponse();
        }
        
        $client_id = get_input('client_id');
        $response_type = get_input('response_type');
        $redirect_uri  = get_input('redirect_uri');
        
        elgg_set_ignore_access(true);
        $client = new entities\client($client_id);
        
        if(!$client->guid){
            var_dump($client);
            return false;
        }
        
        $content = elgg_view('oauth2/authorize', array(
            'entity'        => $client,
            'client_id'     => $client_id,
            'response_type' => $response_type,
            'redirect_uri'  => $redirect_uri,
            'state' => get_input('state')
        ));
        
        $params = array(
            'title'   => $client->title, 
            'content' => $content,
            'filter'  => ''
        );

        $body = elgg_view_layout('one_column', $params);

        echo $this->render(array('title'=>'Authorize', 'body'=>$body));

	}
	
	
	public function post($pages){
	    
      
    }
	

	public function put($pages){}
	

	public function delete($pages){}
	
}
