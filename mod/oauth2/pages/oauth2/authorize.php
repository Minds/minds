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

// Get our custom storage object
$storage = new ElggOAuth2DataStore();

// Create a server instance
$server = new OAuth2_Server($storage);

// Make sure this is a valid authorization request
if (!$server->validateAuthorizeRequest(OAuth2_Request::createFromGlobals())) {
    register_error($server->getResponse());
    forward();
}

if (!$client_id || !$response_type || !$redirect_uri ) {
    forward(REFERER);
}

if (!elgg_get_logged_in_user_guid()) {
    $_SESSION['last_forward_from'] = current_page_url();
    forward('/login');
}

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

$access = elgg_get_ignore_access();
elgg_set_ignore_access(true);

$results = elgg_get_entities_from_metadata($options);

elgg_set_ignore_access($access);

if (empty($results)) {
    forward(REFERER);
}
    
$content = elgg_view('oauth2/authorize', array(
    'entity'        => $results[0],
    'client_id'     => $client_id,
    'response_type' => $response_type,
    'redirect_uri'  => $redirect_uri
));

$params = array(
    'title'   => $results[0]->title, 
    'content' => $content,
    'filter'  => ''
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($results[0]->title, $body);



