<?php

/**
 * Register new OAuth2 application
 * @return type
 */
function oauth2_register_application($minds_username, $minds_password, $url) {	
	
    // Validate user
    if ($result = elgg_authenticate($minds_username, $minds_password) !== true) {
	register_error($result);
        return false;
    }
    
    $user = get_user_by_username($minds_username);
    if (!$user) {
        register_error("No such user");
        return false;
    }
    
    // See if application exists, generate if note
    $oauth_clients = elgg_get_entities(array(
        'type' => 'object',
        'subtype' => 'oauth2_client',
        'wheres' => array('e.description = \''. sanitise_string($url). '\''),
        'limit' => 1
    ));
    
    $client = null;
    if ($oauth_clients)
        $client = $oauth_clients[0]; // Found
    if (!$client) 
    {
        // Not found, create
        
        $client = new ElggObject();
        $client->subtype    = 'oauth2_client';
        $client->owner_guid = $user->guid;
        $client->access_id  = ACCESS_PRIVATE;
        
        $client->title = "Minds connect for '$url'";
        $client->description = $url;
        
        // Save client
        if (!$client->save()) 
            register_error(elgg_echo('oauth2:error:save_failed'));
        
    }
    
    // Create tokens
    $client->client_id     = uniqid($client->guid);
    $client->client_secret = oauth2_generate_client_secret();
    
    return array(
        'client_id' => $client->client_id,
        'client_secret' => $client->client_secret,
    );
}

expose_function('oauth2.application.register', 'oauth2_register_application', array(
    'minds_username' => array(
        'type' => 'string',
    ),
    'minds_password' => array(
        'type' => 'string',
    ),
    'url' => array(
        'type' => 'string',
    )
), "Register an OAuth application", 'GET', $require_api_auth, $require_user_auth);
	