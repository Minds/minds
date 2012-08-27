<?php

global $CONFIG;

try {

	$server = oauth_get_server();
	$req = OAuthRequest::from_request(null, null, oauth_get_params());
	$token = $server->fetch_request_token($req);

	$consumEnt = oauth_lookup_consumer_entity($req->get_parameter('oauth_consumer_key'));
	$tokEnt = oauth_lookup_token_entity($token->key, 'request', $consumEnt);

	if ($consumEnt->revA) {
		// make sure the callback url is a proper extension of the registered one if it exists
		if ($consumEnt->callbackUrl) {
			if (!$tokEnt->callbackUrl || !strstr($tokEnt->callbackUrl, $consumEnt->callbackUrl)) {
				throw new OAuthException('Callback URL does not match registered value');
			}
		}
	}

	// save the nonce
	$consumerKey = $req->get_parameter('oauth_consumer_key');
	$nonce = $req->get_parameter('oauth_nonce');
	// save our nonce for later checking
	oauth_save_nonce($consumerKey, $nonce);

	echo $token;

	if ($consumEnt->revA) {
		// add the callback confirmed tag
		echo '&oauth_callback_confirmed=true';
	}

} catch (OAuthException $e) {
	header('', true, 401); // return HTTP 401: Not Authorized

	echo $e->getMessage() . "\n<hr />\n";

	die();
}
