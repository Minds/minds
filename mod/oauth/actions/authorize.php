<?php

global $CONFIG;

// must be logged in to use this page
gatekeeper();

$user = get_loggedin_user();

// TODO: check against oauth-based login to disable one token signing another


$tokenKey = get_input('oauth_token');

$tokEnt = oauth_lookup_token_entity($tokenKey, 'request');

if ($tokEnt) {

	// sign the token and tie it to this user

	$tokEnt->owner_guid = $user->getGUID();
	$tokEnt->container_guid = $user->getGUID();
    
	$tokEnt->save();

	// get our consumer
	$consumEnt = oauth_lookup_consumer_entity($tokEnt->consumerKey);

	if ($consumEnt->revA) {
		// Rev A requires we create a verifier

		$verifier = oauth_generate_verifier();
		$tokEnt->verifier = $verifier;
		
		$url = $tokEnt->callbackUrl;

		// make sure the callback url is a proper extension of the registered one if it exists
		if ($consumEnt->callbackUrl) {
			if (!$url || !strstr($url, $consumEnt->callbackUrl)) {
				$url = $consumEnt->callbackUrl;
			}
		}

		if ($url && $url != 'oob') {
 
			// Pick the correct separator to use
			$separator = "?";
			if (strpos($url,"?")!==false) {
				$separator = "&";
			}

			// Find the location for the new parameter
			$insertPosition = strlen($url); 
			if (strpos($url,"#")!==false) {
				$insertPosition = strpos($url,"#");
			}
			
			// Build the new url
			$newUrl = substr_replace($url, $separator . 'oauth_verifier=' . $tokEnt->verifier . '&oauth_token=' . $tokEnt->requestToken . '&oauth_callback_confirmed=true', $insertPosition, 0);
			
			// can't use system's built-in forward() method in case of non-http URLs
			header("Location: {$newUrl}");
			exit(); // stop the action processor to kill the auto forward
		} else {
			system_message('You have authorized ' . $consumEnt->name);

			// out of band callback
			forward($CONFIG->wwwroot . 'pg/oauth/authorize?oauth_verifier=' . $tokEnt->verifier . '&authorized=' . $tokEnt->requestToken);
		}
	} else {
		// otherwise we follow the callback passed in if it's there

		$callback = get_input('oauth_callback');

		if ($callback) {
			header("Location: {$callback}");
			exit(); // stop the action processor to kill the auto forward
		} else {
			// out of band callback
			system_message('You have authorized ' . $consumEnt->name);

			forward($CONFIG->wwwroot . 'pg/oauth/authorize?authorized=' . $tokEnt->requestToken);
		}
	}
} else {

	register_error('There was an error registering this application.');

	forward($_SERVER['HTTP_REFERRER']);

}

