<?php

  // must be logged in
gatekeeper();

global $CONFIG;

$verifier = get_input('oauth_verifier', NULL);
$return_token_key = get_input('oauth_token', NULL);

// get our saved request token
$saved_token_guid = $_SESSION['oauth_token'];
$return_to = $_SESSION['oauth_return_to'];
$access_url = $_SESSION['oauth_access_url'];

$tokEnt = get_entity($saved_token_guid);

if ($tokEnt 
    && $tokEnt->getOwner() == get_loggedin_user()->getGUID()
    && (!($return_token_key) || ($tokEnt->requestToken == $return_token_key))) {

	$request_token = oauth_token_from_entity($tokEnt);

	$consumEnt = oauth_lookup_consumer_entity($tokEnt->consumerKey);
	$consumer = oauth_consumer_from_entity($consumEnt);

	if ($consumEnt->revA) {
		$access_token = oauth_get_new_access_token($consumer, $tokEnt, $access_url, $verifier);
	} else {
		$access_token = oauth_get_new_access_token($consumer, $tokEnt, $access_url);
	}
	
	if ($access_token) {
		// save the access token over our existing request token
		oauth_save_access_token($tokEnt, $access_token);
		system_message(sprintf(elgg_echo('oauth:success', $consumEnt->name)));
	} else {
		// get rid of our bad token and try again
		$tokEnt->delete();
		register_error(sprintf(elgg_echo('oauth:failure', $consumEnt->name)));
	}
} else {
	register_error(elgg_echo('oauth:tokenfail'));
}

// clean up the SESSION
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_return_to']);
unset($_SESSION['oauth_access_url']);

forward($return_to);
