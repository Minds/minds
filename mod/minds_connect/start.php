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

function minds_connect_link($username, $password, $access_token, $refresh_token, $expires) {

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

    setcookie("MC", $access_token, strtotime('+1 year'), "/");

    return $user->guid;
}


