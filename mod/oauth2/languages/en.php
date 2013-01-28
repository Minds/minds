<?php

/**
 * OAuth2 English language file.
 *
 */

$english = array(
	'item:object:oauth2_client'        => 'OAuth2 Clients',
	'item:object:oauth2_access_token'  => 'OAuth2 Access Tokens',
	'item:object:oauth2_refresh_token' => 'OAuth2 Refresh Tokens',
	'item:object:oauth2_auth_code'     => 'OAuth2 Authoriation Codes',
	'oauth2:register:title'            => 'Register Application',
    'oauth2:applications:admin_title'  => 'Registered Applications',
    'oauth2:applications:title'        => 'Your Registered Applications',

    // Forms
    'oauth2:name:label' => 'Display Name',
    'oauth2:url:label'  => 'Application URL',

    // Register action
    'auth2:register:app_not_found' => 'Failed to update application',
    'oauth2:register:updated'      => 'Successfully updated application',
    'oauth2:register:registered'   => 'Successfully registered application',
);

add_translation('en', $english);
