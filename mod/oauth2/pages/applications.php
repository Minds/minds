<?php
/**
 * OAuth2::applications
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

class applications extends core\page implements Interfaces\page{
	
	
	public function get($pages){
	    
        
        
        $content = elgg_view_form('oauth2/register', array('action'=>'oauth2/applications'));
        
        $content .= core\Entities::view(array(
            'type'       => 'object',
            'subtype'    => 'oauth2_client',
            'owner_guid' => elgg_get_logged_in_user_guid(),
            'limit'      => 10,    
            'full_view'  => false,
            'list_class' => 'x1',
            'masonry' => false));
        
        
        $params = array(
            'title'   => 'OAuth2 Applications', 
            'content' => $content,
            'filter'  => ''
        );

        $body = elgg_view_layout('content', $params);

        echo $this->render(array('title'=>'Authorize', 'body'=>$body));

	}
	
	
	public function post($pages){
	    
        $guid = get_input('guid');
        $title = get_input('name');
        $description = get_input('url');
        $secret = get_input('secret');

        if ($guid) {
        
            $entity = get_entity($guid,'object');
        
            if (!elgg_instanceof($entity, 'object', 'oauth2_client') || !$entity->canEdit()) {
                register_error(elgg_echo('oauth2:register:app_not_found'));
                forward(REFERRER);
            }
        
        } else {
        
            $entity = new entities\client();
            $entity->subtype    = 'oauth2_client';
            $entity->owner_guid = elgg_get_logged_in_user_guid();
            $entity->access_id  = ACCESS_PRIVATE;
        }
        
        $entity->title       = $title;
        $entity->description = $description;
        
        if (!$entity->save()) {
            register_error(elgg_echo('oauth2:error:save_failed'));
            forward(REFERRER);
        }
        
        if (!$guid) {
            $entity->client_id     = $entity->guid;
            $entity->client_secret = \minds\plugin\oauth2\start::generateSecret();
        }
        
        if ($secret) {
            $entity->client_secret = $secret;
        }
        
        $entity->save();
      
        $this->forward(REFERRER);
    }
	

	public function put($pages){}
	

	public function delete($pages){}
	
}
