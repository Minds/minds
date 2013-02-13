<?php

/**
 * Minds Connect authorization page
 *
 * @author Billy Gunn (billy@arckinteractive.com)
 * @copyright Minds.com 2013
 * @link http://minds.com
 */

$client_id     = get_input('client_id');
$response_type = get_input('response_type');
$redirect_uri  = get_input('redirect_uri');

$access = elgg_get_ignore_access();
elgg_set_ignore_access(true);

// Get our custom storage object
$storage = new ElggOAuth2DataStore();

// Create a server instance
$server = new OAuth2_Server($storage);

// Make sure this is a valid authorization request
if (!$server->validateAuthorizeRequest(OAuth2_Request::createFromGlobals())) {
    register_error($server->getResponse());
    error_log('validateAuthorizeRequest: ' . $server->getResponse());
    elgg_set_ignore_access($access);
    forward(); 
}

if (!$client_id || !$response_type || !$redirect_uri ) {
    forward(REFERER);
}

// Send the user to the login page if they are not already loged in
if (!elgg_get_logged_in_user_guid()) {
    $_SESSION['last_forward_from'] = current_page_url();
    forward('/login');
}

// Get the client record
$options = array(
    'type'    => 'object',
    'subtype' => 'oauth2_client',
    'metadata_name_value_pairs' => array(
        'name'    => 'client_id',
        'value'   => $client_id,
        'operand' => '='
    ),
    'limit' => 1,
);

$client = elgg_get_entities_from_metadata($options);

if (empty($client)) {
    forward(REFERER);
}

// At this point check to see if the user has already authorized
// this app.

$options = array(
    'type'    => 'object',
    'subtype' => 'oauth2_refresh_token',
    'owner_guid' => elgg_get_logged_in_user_guid(),
    'container_guid' => $client[0]->guid,
    'limit' => 1,
);

$token = elgg_get_entities($options);

// If already authorized return the user with an access token.
if (!empty($token)) {
    $server->addGrantType(new OAuth2_GrantType_AuthorizationCode($storage));
    $server->handleAuthorizeRequest(OAuth2_Request::createFromGlobals(), true, elgg_get_logged_in_user_guid())->send();
    exit;
}

elgg_set_ignore_access($access);


// Show the autorization pae if the user has not already 
// authorized this app. 
    
$content = elgg_view('oauth2/authorize', array(
    'entity'        => $client[0],
    'client_id'     => $client_id,
    'response_type' => $response_type,
    'redirect_uri'  => $redirect_uri
));

$params = array(
    'title'   => $client[0]->title, 
    'content' => $content,
    'filter'  => ''
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($client[0]->title, $body);



