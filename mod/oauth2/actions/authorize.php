<?php

// Our user should be logged in here
gatekeeper();

// Load our library methods
elgg_load_library('oauth2');

// Get our custom storage object
$storage = new ElggOAuth2DataStore();

// Create a server instance
$server = new OAuth2_Server($storage);

$server->addGrantType(new OAuth2_GrantType_AuthorizationCode($storage));

$access = elgg_get_ignore_access();
elgg_set_ignore_access(true);

$server->handleAuthorizeRequest(OAuth2_Request::createFromGlobals(), true, elgg_get_logged_in_user_guid())->send();

elgg_set_ignore_access($access);

exit;
