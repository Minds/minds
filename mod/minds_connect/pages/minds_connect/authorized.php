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
    'grant_type' => 'authorization_code',
    'code'       => $code,
    'client_id'  => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri'  => $callback,
);

$endpoint = "{$minds_url}/oauth2/grant";

// call the API using Curl
$curl = new MC_Curl();

$response = $curl->request($endpoint, $query, 'POST');

$response = json_decode($response['response'], true);

if (!isset($response['access_token'])) {
    register_error('Failed to retrieve an access token');
    print_r($response); exit; 
    //forward();
}

//    [access_token] => e06b6fe23ac48f75e02aa5992f140c9866c5e240 
//    [expires_in] => 3600 
//    [token_type] => bearer 
//    [scope] => 
//    [refresh_token] => 20df12522a63fedde56c6340c570f03071dc8ad7 )

// get_user

$query = array(
    'client_id'     => $client_id,
    'client_secret' => $client_secret,
    'access_token'  => $response['access_token'],
    'redirect_uri'  => $callback,
);

$endpoint = "{$minds_url}/oauth2/get_user";

$user_response = $curl->request($endpoint, $query, 'GET');

print_r($user_response);

exit;

 
