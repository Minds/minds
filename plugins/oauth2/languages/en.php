<?php

/**
 * OAuth2 English language file.
 *
 */

$english = array(

    // Object subtypes
    'item:object:oauth2_client'        => 'OAuth2 Clients',
    'item:object:oauth2_access_token'  => 'OAuth2 Access Tokens',
    'item:object:oauth2_refresh_token' => 'OAuth2 Refresh Tokens',
    'item:object:oauth2_auth_code'     => 'OAuth2 Authoriation Codes',

    'admin:settings:oauth2' => 'Connected Applications',
    'oauth2:register:title'            => 'Register Application',
    'oauth2:applications:admin_title'  => 'Registered Applications',
    'oauth2:applications:title'        => 'Your Registered Applications',
    'oauth2:add'                       => 'Register Application',
    'oauth2:authorize'                 => '%s is requesting access to your personal information in order to connect to minds.com.',
    'oauth2:register:app_not_found'    => 'Application not found',
    'oauth2:error:save_failed'         => 'Failed to save application',
    'oauth2:developers'                => 'Developers',
    'oauth2'                           => 'Minds Connect',

    // Forms
    'oauth2:name:label'           => 'Application Name',
    'oauth2:url:label'            => 'Application URL',
    'oauth2:client_id:label'      => 'Client ID',
    'oauth2:client_secret:label'  => 'Client Secret',
    'oauth2:regenerate:confirm'   => 'Regenerating your secret will prevent any applications currently using this secret to fail once you click Save. Continue?',

    // Register action
    'auth2:register:app_not_found' => 'Failed to update application',
    'oauth2:register:updated'      => 'Successfully updated application',
    'oauth2:register:registered'   => 'Successfully registered application',

    // Delete action
    'oauth2:application:not_found'     => 'Application not found',
    'oauth2:application:deleted'       => 'The application was successfully deleted',
    'oauth2:application:cannot_delete' => 'Failed to delete the application',
);

add_translation('en', $english);
