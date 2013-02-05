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

// Get a new access token
$token = minds_connect_refresh_token($user);

elgg_set_ignore_access($access);


/*
 * The refresh token was possibly expired on the server side. 
 * Allow the user to re-authorize the app.
 */
if (!isset($token['access_token'])) {

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


/* Get the Minds user */

$query = array(
    'client_id'     => $client_id,
    'client_secret' => $client_secret,
    'access_token'  => $token['access_token'],
    'redirect_uri'  => $callback,
);

$endpoint = "{$minds_url}/oauth2/get_user";

$curl = new MC_Curl();

$response = $curl->request($endpoint, $query, 'GET');

$response = array_merge($token, json_decode($response['response'], true));

if (!$response['username']) {
    register_error('Failed to retrieve user record');
    forward();
}


/* Log the user in */

try {

    login($user);

    // Set the updated token
    $user->mc_access_token  = $response['access_token'];
    $user->mc_expires       = $response['expires'] + time();

    setcookie("MC", $response['access_token'], strtotime('+1 year'), "/");

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


