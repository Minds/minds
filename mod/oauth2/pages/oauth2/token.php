<?php
/**
 * Minds Connect token page (mainly for password auth)
 *
 */
 
if(elgg_is_logged_in()){
	echo "you are already logged in...";
	exit;
}
$ia = elgg_set_ignore_access(true);

// Get our custom storage object
$storage = new ElggOAuth2DataStore();

// Create a server instance
$server = new OAuth2_Server($storage);

$server->addGrantType(new OAuth2_GrantType_UserCredentials($storage));
$server->handleGrantRequest(OAuth2_Request::createFromGlobals())->send();

elgg_set_ignore_access($ia);
