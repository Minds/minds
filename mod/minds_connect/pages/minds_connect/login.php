<?php

$minds_url     = get_plugin_setting('minds_url', 'minds_connect');
$client_id     = get_plugin_setting('client_id', 'minds_connect');
$client_secret = get_plugin_setting('client_secret', 'minds_connect');

$options = array(
    'type' => 'user',
    'metadata_name_value_pairs' => array(
        'name'    => 'mc_access_token',
        'value'   => $_COOKIE['MC'],
        'operand' => '=',
    ),
    'limit' => 1
);

$access = elgg_get_ignore_access();
elgg_set_ignore_access(true);

$entities = elgg_get_entities_from_metadata($options);


if (empty($entities)) {

    $ssl_callback = get_plugin_setting('ssl_callback', 'minds_connect');

    if ($ssl_callback == 'yes') {
        $parts    = parse_url(elgg_get_site_url());
        $callback = 'https://' . $parts['host'] . '/minds_connect/authorized';
    } else {
        $callback = elgg_get_site_url() . 'minds_connect/authorized';
    }

    $callback = urlencode($callback);

    $url = "{$minds_url}/oauth2/authorize?response_type=code&client_id={$client_id}&redirect_uri={$callback}";

    forward($url);
}

// Get the refresh token
$user = $entities[0];
$refresh_token = $user->mc_refresh_token;

elgg_set_ignore_access($access);


/* Get a new access token */

$query = array(
    'grant_type'    => 'refresh_token',
    'refresh_token' => $refresh_token,
    'client_id'     => $client_id,
    'client_secret' => $client_secret,
);

$endpoint = "{$minds_url}/oauth2/grant";

$curl = new MC_Curl();

$response = $curl->request($endpoint, $query, 'POST');

$response = json_decode($response['response'], true);

if (!isset($response['access_token'])) {
    register_error('Failed to retrieve an access token');
    error_log(print_r($response, true));
    forward();
}


/* Get the Minds user */

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


/* Log the user in */

try {

    login($user);

    // Store the updated tokens
    $user->mc_access_token  = $access_token;
    $user->mc_refresh_token = $refresh_token;
    $user->mc_expires       = $expires;

    setcookie("MC", $access_token, strtotime('+5 year'), "/");

    register_translations(dirname(dirname(__FILE__)) . "/languages/");

} catch (LoginException $e) {
    register_error($e->getMessage());
    forward(REFERER);
}

if ($user->language) {
    $message = elgg_echo('loginok', array(), $user->language);
} else {
    $message = elgg_echo('loginok');
}

system_message($message);

forward();


