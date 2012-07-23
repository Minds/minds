<?php

  // must be logged in to use this page

admin_gatekeeper();

$user = get_loggedin_user();
$name = get_input('name');
$desc = get_input('desc');
$callback = get_input('callback');
$revA = get_input('reva', False) ? True : False; // force a boolean
$outbound = get_input('outbound', False) ? True : False; // force a boolean
$key = get_input('key');
$secret = get_input('secret');

$name = trim($name);
$desc = trim($desc);
$key = trim($key);
$secret = trim($secret);

if ($name && $desc) {

	if (!$key || !$secret) {
		// generate a key and secret
		$key = md5(time());
		$secret = md5(md5(time() + time()));
	}

	// create a new entity
	$consumEnt = oauth_create_consumer($name, $desc, $key, $secret, $revA, $outbound, $callback);

	//
	// NOTE: 
	//   this action and its associated pages are intended for
	//   inbound clients only.
	// 
	//   outbound consumers should be registered indirectly 
	//   by the plugins implementing the oauth client.
	//

	system_message('Your application, ' . $name . ' has been successfully registered. Configure the client with the key and secret below.');
    
} else {
	register_error('You must fill out both the name and description fields.');
}


forward($_SERVER['HTTP_REFERER']);
