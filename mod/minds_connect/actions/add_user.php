<?php


$access_token  = $_SESSION['minds_connect']['access_token'];
$refresh_token = $_SESSION['minds_connect']['refresh_token'];
$expires       = $_SESSION['minds_connect']['expires'];
$minds_guid    = $_SESSION['minds_connect']['guid'];
$type          = get_input('type');
$status        = null;


if ($type == 'link') {

    /* Link accounts */

    $username = get_input('username');
    $password = get_input('password');

    $status = minds_connect_link($username, $password, $access_token, $refresh_token, $expires, $minds_guid);

} else if ($type == 'register') {

    /* Register and link accounts */

    $name      = get_input('name');
    $username  = get_input('username');
    $email     = get_input('email');
    $password  = get_input('password', null, false);
    $password2 = get_input('password2', null, false);

    if (empty($username) || empty($password) || empty($name) || empty($email)) {
        register_error(elgg_echo('minds_connect:register:empty'));
        forward(REFERER);
    }

    if (strcmp($password, $password2) != 0) {
        register_error(elgg_echo('RegistrationException:PasswordMismatch'));
        forward(REFERER);
    }

    $status = minds_connect_register($name, $email, $username, $password, $access_token, $refresh_token, $expires, $minds_guid);
}

if (!$status) {
    register_error(elgg_echo('minds_connect:link:failed'));
} else {
    system_message(elgg_echo('minds_connect:link:success'));
}

forward();

