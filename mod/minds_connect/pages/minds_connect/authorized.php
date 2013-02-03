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
 

// Store the access token in the session
$_SESSION['minds_connect']['access_token']  = $response['access_token'];
$_SESSION['minds_connect']['refresh_token'] = $response['refresh_token'];
$_SESSION['minds_connect']['expires']       = time() + $response['expires'];

// Add or link the user
$content = elgg_view('minds_connect/add_user', array('data' => $response));

$params = array(
    'title'   => 'Minds Connect',
    'content' => $content,
    'filter'  => ''
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page('Minds Connect', $body);


