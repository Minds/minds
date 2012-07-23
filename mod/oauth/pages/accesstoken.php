<?php

global $CONFIG;

/*
 $cid = get_input('oauth_consumer_key');
 $tid = get_input('oauth_token');
 $consumEnt = oauth_lookup_consumer_entity($cid);
 print 'Consumer: ' . $cid;
 print_r($consumEnt);
 print_r(oauth_consumer_from_entity($consumEnt));
 $tokEnt = oauth_lookup_token_entity($consumEnt, 'request', $tid);
 print 'Token: ' . $tid;
 print_r($tokEnt);
 print_r(oauth_token_from_entity($tokEnt));
*/

try {
	$server = oauth_get_server();
	$req = OAuthRequest::from_request(null, null, oauth_get_params());
	$token = $server->fetch_access_token($req);

	// save the nonce

	$consumerKey = $req->get_parameter('oauth_consumer_key');
	$tokenKey = $req->get_parameter('oauth_token');
	$nonce = $req->get_parameter('oauth_nonce');
	// save our nonce for later checking
	oauth_save_nonce($consumerKey, $nonce, $tokenKey);

	echo $token;
} catch (OAuthException $e) {
	header('', true, 401); // return HTTP 401: Not Authorized

	echo $e->getMessage() . "\n<hr />\n";

	die();
}
