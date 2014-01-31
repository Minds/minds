<?php

// Load and register the autoloader for the OAuth2 library
require_once(elgg_get_plugins_path() . 'oauth2/lib/OAuth2/Autoloader.php');

OAuth2_Autoloader::register();

/*
 * Handle grant requests
 */
function oauth2_grant() {

    // Get our custom storage object
    $storage = new ElggOAuth2DataStore();

    // Create a server instance
    $server = new OAuth2_Server($storage);

    // Client entities are private so ignore access during the lookup
    $access = elgg_get_ignore_access();
    elgg_set_ignore_access(true);

    $server->handleGrantRequest(OAuth2_Request::createFromGlobals())->send();

    elgg_set_ignore_access($access);

    return;
}

function oauth2_get_user_by_access_token() {

    $access = elgg_get_ignore_access();
    elgg_set_ignore_access(true);

    // Get our custom storage object
    $storage = new ElggOAuth2DataStore();

    // Create a server instance
    $server = new OAuth2_Server($storage);

    // Validate the request
    if (!$server->verifyAccessRequest(OAuth2_Request::createFromGlobals())) {
        elgg_set_ignore_access($access);
        return false;
    }

    // Get the token data
    $token = $storage->getAccessToken(get_input('access_token'));

    elgg_set_ignore_access($access);

    // get the user associated with this token
    $user = get_entity($token['user_id'],'user');
    
    echo json_encode(array(
        'guid'     => $user->guid,
        'username' => $user->username,
        'name'     => $user->name,
        'email'    => $user->email,
    ));
}

/**
 * Generates and returns a Version 4 UUID
 *
 * http://www.php.net/manual/en/function.uniqid.php#94959
 *
 * @return string
 */
function oauth2_generate_client_secret() {

    return sprintf( '%04x%04x%04x%04x%04x%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
