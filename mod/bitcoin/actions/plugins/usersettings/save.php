<?php

gatekeeper();

try {

    if ($wallet_guid = get_input('wallet_guid')) {
	$address = get_input('address');
	//if (!$address = get_input('address'))
	//	throw new Exception ('You must specify a bitcoin address');

	$password = get_input('password');

	if (!$result = \minds\plugin\bitcoin\bitcoin()->importWallet($wallet_guid, $address, $password))
	    throw new \Exception('Could not import wallet');
    }
    /*else
    // Ok, if we haven't got an address, then we probably should generate one
    if (!elgg_get_plugin_user_setting('bitcoin_address', elgg_get_logged_in_user_guid(), 'bitcoin') && !get_input('wallet_guid')) {

	if ($user = elgg_get_logged_in_user_entity()) {

	    if (!$wallet = minds\plugin\bitcoin\bitcoin()->getWallet($user)) {
		$wallet_guid = minds\plugin\bitcoin\bitcoin()->createWallet($user);
		$wallet = get_entity($wallet_guid);
	    }

	    if (!$wallet)
		throw new Exception("Wallet could not be created...");

	    system_message("You don't appear to have a central bitcoin account, so we've created one for you. If you like, you can import an existing one.");
	} else
	    throw new Exception("Could not get user...");
    }*/
} catch (Exception $ex) {
    error_log("Bitcoin: " . $ex->getMessage());
    register_error($ex->getMessage());
}