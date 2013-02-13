<?php

if (!$code = get_input('code')) {
    forward();
}

$minds_url     = get_plugin_setting('minds_url', 'minds_connect');
$client_id     = get_plugin_setting('client_id', 'minds_connect');
$client_secret = get_plugin_setting('client_secret', 'minds_connect');
$ssl_callback  = get_plugin_setting('ssl_callback', 'minds_connect');

if ($ssl_callback == 'yes') {
    $parts    = parse_url(elgg_get_site_url());
    $callback = 'https://' . $parts['host'] . '/minds_connect/authorized';
} else {
    $callback = elgg_get_site_url() . 'minds_connect/authorized';
}

$callback = urlencode($callback);

// exchange authorization code for access token
$query = array(
    'grant_type'    => 'authorization_code',
    'code'          => $code,
    'client_id'     => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri'  => $callback,
);

$endpoint = "{$minds_url}/oauth2/grant";


/* Get an access token */

$curl = new MC_Curl();

$response = $curl->request($endpoint, $query, 'POST');

$response = json_decode($response['response'], true);

if (!isset($response['access_token'])) {
    register_error('Failed to retrieve an access token');
    error_log('QUERY: ' . print_r($query, true));
    error_log('RESPONSE: ' . print_r($response, true));
    forward();
}


/* Get the minds user record */

$query = array(
    'client_id'     => $client_id,
    'client_secret' => $client_secret,
    'access_token'  => $response['access_token'],
    'redirect_uri'  => $callback,
);

$endpoint = "{$minds_url}/oauth2/get_user";

$user_response = $curl->request($endpoint, $query, 'GET');

$response = array_merge($response, json_decode($user_response['response'], true));

if (!$response['username']) {
    register_error('Failed to retrieve user record');
    forward();
}
 
// Check to see if the users are already linked
$user = elgg_get_entities_from_metadata(array(
    'type' => 'user',
    'limit' => 1,
    'metadata_name_value_pairs' => array(
        'name'    => 'mc_guid',
        'value'   => $response['guid'],
        'operand' => '='
    ),
));
 
if ($user[0]) {

    $user = $user[0];

    // Log the user in
    try {
        login($user);
        // re-register at least the core language file for users with language other than site default
        register_translations(dirname(dirname(__FILE__)) . "/languages/");
    } catch (LoginException $e) {
        register_error($e->getMessage());
        return null;
    }

    // Add the oauth 2 metadata
    $user->mc_access_token  = $response['access_token'];
    $user->mc_refresh_token = $response['refresh_token'];
    $user->mc_expires       = $response['expires'];

    setcookie("MC", $response['access_token'], strtotime('+1 year'), "/");

    forward();
}


// Check to see if the minds user exists on this site already
$link    = null;
$results = get_user_by_email($response['email']);

if (empty($results)) {
    if ($user = get_user_by_username($response['username'])) {
        $link = 'username';
    }
} else {
    $user = $results[0];
    $link = 'email';
}

/*
 * If the user exists on this site give the user the option
 * of linking their accounts or registering a new account.
 *
 * If the user does not exist, auto register them and send a 
 * password reset notification.
 *
 */
if ($link) {

    // Store the access token in the session
    $_SESSION['minds_connect']['access_token']  = $response['access_token'];
    $_SESSION['minds_connect']['refresh_token'] = $response['refresh_token'];
    $_SESSION['minds_connect']['expires']       = time() + $response['expires'];
    $_SESSION['minds_connect']['guid']          = $response['guid'];

    // Add or link the user
    $content = elgg_view('minds_connect/add_user', array('data' => $response, 'link' => $link, 'user' => $user));

    $params = array(
        'title'   => 'Minds Connect',
        'content' => $content,
        'filter'  => ''
    );

    $body = elgg_view_layout('content', $params);

    echo elgg_view_page('Minds Connect', $body);

} else {

    // Register the user

    $name          = $response['name'];
    $email         = $response['email'];
    $username      = $response['username'];
    $access_token  = $response['access_token'];
    $refresh_token = $response['refresh_token'];
    $expires       = time() + $response['expires'];
    $minds_guid    = time() + $response['guid'];

    $guid = minds_connect_register($name, $email, $username, null, $access_token, $refresh_token, $expires, $minds_guid);

    forward();
}


