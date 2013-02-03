<?php

// Our user should be logged in here
gatekeeper();

// Load our library methods
elgg_load_library('oauth2');

// Get our custom storage object
$storage = new ElggOAuth2DataStore();

// Create a server instance
$server = new OAuth2_Server($storage);

/* 
 * At this point we only support the Authorization Code grant
 *
 * http://tools.ietf.org/html/draft-ietf-oauth-v2-31#section-4.1
 */
$server->addGrantType(new OAuth2_GrantType_AuthorizationCode($storage));

$server->handleAuthorizeRequest(OAuth2_Request::createFromGlobals(), true, elgg_get_logged_in_user_guid())->send();

return true;
