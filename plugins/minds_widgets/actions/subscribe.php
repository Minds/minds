<?php

$channel_guid = get_input('channel_guid');
$user = get_entity($channel_guid,'user');

try {
    if (!$user) {
        register_error("No such channel!");
        forward();
    }


    // Going to assume that subscribe means "friend"
    $loggedin = elgg_get_logged_in_user_entity();

    if ($return = user_friend_add($loggedin->username, $user->username)) {
        system_message($return['message']);
        forward($user->getUrl());
    } else {
        register_error("There was a problem subscribing to channel...");
    }
} catch (Exception $e) {
    register_error($e->getMessage());
}
