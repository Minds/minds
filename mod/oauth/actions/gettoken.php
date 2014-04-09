<?php

  // must be logged in
gatekeeper();

global $CONFIG;

// Get the logged in user
$user = get_loggedin_user();

$consumer_key = get_input('consumer_key');
$return_to = html_entity_decode(get_input('return_to'));
$user_auth = html_entity_decode(get_input('user_auth'));
$request_url = html_entity_decode(get_input('request_url'));
$access_url = html_entity_decode(get_input('access_url'));

// make our consumer object
$consumEnt = oauth_lookup_consumer_entity($consumer_key);
$consumer = oauth_consumer_from_entity($consumEnt);

// get a new request token
if ($consumEnt->revA) {
	$token = oauth_get_new_request_token($consumer, $request_url, $consumEnt->callbackUrl);
} else {
	$token = oauth_get_new_request_token($consumer, $request_url);
}

if ($token != null) {
// save our token
    if ($consumEnt->revA) {
        $tokEnt = oauth_save_request_token($token, $consumer, $user, $consumEnt->callbackUrl);
    } else {
        $tokEnt = oauth_save_request_token($token, $consumer, $user);
    }

// save our information to the session and send the user off to get the token validated
    $_SESSION['oauth_return_to'] = $return_to;
    $_SESSION['oauth_token'] = $tokEnt->getGUID();
    $_SESSION['oauth_access_url'] = $access_url;

//print_r($tokEnt);
    if ($consumEnt->revA) {
        // Rev A change in protocol flow
        $url = sprintf($user_auth, urlencode($tokEnt->requestToken));
    } else {
        $url = sprintf($user_auth, urlencode($tokEnt->requestToken), urlencode($consumEnt->callbackUrl));
    }

//print $url;
//die();
// forward offsite
    forward($url);
} else {
    register_error(elgg_echo('oauth:tokenfail'));
    forward($return_to);
}
