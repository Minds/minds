<?php

/**
 * Minds Connect - OAuth2 client implementation
 *
 * @author Billy Gunn (billy@arckinteractive.com)
 * @copyright Minds.com 2013
 * @link http://minds.com
 */

elgg_register_event_handler('init','system','minds_connect_init');

function minds_connect_init() {

    $base = elgg_get_plugins_path() . 'minds_connect';

    // Register our OAuth2 storage implementation
    elgg_register_class('MC_Curl', "$base/lib/MC_Curl.php");

    // Our callback page handler
    elgg_register_page_handler('minds_connect', 'minds_connect_page_handler');

    // Send wire posts to minds.com
    elgg_register_event_handler('create', 'object', 'minds_connect_wire_posts');

    // Extend the login form to add a Minds Connect button
    elgg_extend_view('forms/login', 'minds_connect/connect');

    // Register actions
    elgg_register_action('minds_connect/add_user', $base . '/actions/add_user.php', 'public');
}

function minds_connect_page_handler($page) {

    $base = elgg_get_plugins_path() . 'minds_connect';

    $pages = $base . '/pages/minds_connect';

    switch ($page[0]) {

        case 'authorized':
            require $pages . "/authorized.php";        
            break;

        case 'login':
            require $pages . "/login.php";        
            break;
    }

    return true;
}

function minds_connect_wire_posts($event, $object_type, $object) {

    $user = elgg_get_logged_in_user_entity();

    if ($object->getSubtype() != 'thewire' || !$user->mc_refresh_token) {
        return true;
    }

    $minds_url     = get_plugin_setting('minds_url', 'minds_connect');
    $client_id     = get_plugin_setting('client_id', 'minds_connect');
    $client_secret = get_plugin_setting('client_secret', 'minds_connect');

    $token = minds_connect_refresh_token();

    $endpoint = "{$minds_url}/services/api/rest/json";

    $query = array(
        'method'        => 'wire.save_post',
        'text'          => $object->description,
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'access_token'  => $token['access_token'],
    );

    $curl = new MC_Curl();

    $response = $curl->request($endpoint, $query, 'POST');

    return true;
}

function minds_connect_refresh_token($user=null) {

    if (!$user) {
        if (!$user = elgg_get_logged_in_user_entity()) {
            register_error('User not found');
            forward();
        }
    }

    $minds_url     = get_plugin_setting('minds_url', 'minds_connect');
    $client_id     = get_plugin_setting('client_id', 'minds_connect');
    $client_secret = get_plugin_setting('client_secret', 'minds_connect');

    $query = array(
        'grant_type'    => 'refresh_token',
        'refresh_token' => $user->mc_refresh_token,
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
    );

    $endpoint = "{$minds_url}/oauth2/grant";

    $curl = new MC_Curl();

    $response = $curl->request($endpoint, $query, 'POST');

    $response = json_decode($response['response'], true);

    // Store the updated tokens if the user is logged in
    if (elgg_get_logged_in_user_guid()) {
        $user->mc_access_token  = $response['access_token'];
        $user->mc_expires       = $response['expires'] + time();

        setcookie("MC", $response['access_token'], strtotime('+1 year'), "/");
    }

    return $response;
}

function minds_connect_link($username, $password, $access_token, $refresh_token, $expires, $minds_guid) {

    if (empty($username) || empty($password)) {
        register_error(elgg_echo('login:empty'));
        return null;
    }

    // check if logging in with email address
    if (strpos($username, '@') !== false && ($users = get_user_by_email($username))) {
        $username = $users[0]->username;
    }

    // Check the supplied credentials
    $result = elgg_authenticate($username, $password);
    if ($result !== true) {
        register_error($result);
        return null;
    }

    $user = get_user_by_username($username);

    if (!$user) {
        register_error(elgg_echo('login:baduser'));
        return null;
    }

    // Log the user in
    try {
        login($user, $persistent);
        // re-register at least the core language file for users with language other than site default
        register_translations(dirname(dirname(__FILE__)) . "/languages/");
    } catch (LoginException $e) {
        register_error($e->getMessage());
        return null;
    }

    // Add the oauth 2 metadata
    $user->mc_access_token  = $access_token;
    $user->mc_refresh_token = $refresh_token;
    $user->mc_expires       = $expires;
    $user->mc_guid          = $minds_guid;

    setcookie("MC", $access_token, strtotime('+1 year'), "/");

    return $user->guid;
}

/*
 * Auto register a user from minds.com and send them
 * their password.
 *
 */
function minds_connect_register($name, $email, $username, $password=null, $access_token, $refresh_token, $expires, $minds_guid) {

    /* Register and link accounts */

    if (!$password) {
        $password  = generate_random_cleartext_password();
    }

    try {
        $guid = register_user($username, $password, $name, $email, false);
    } catch (RegistrationException $r) {
        register_error($r->getMessage());
        forward(REFERER);
    }

    $new_user = get_user($guid);

    // Validate the user
    create_metadata($guid, 'validated', TRUE, '', 0, ACCESS_PUBLIC);

    $subject = elgg_echo('useradd:subject');
    $body = elgg_echo('useradd:body', array(
        $name,
        elgg_get_site_entity()->name,
        elgg_get_site_entity()->url,
        $username,
        $password,
    ));

    notify_user($new_user->guid, elgg_get_site_entity()->guid, $subject, $body);

    $status = minds_connect_link($username, $password, $access_token, $refresh_token, $expires, $minds_guid);

    return $guid;
}

