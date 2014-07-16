<?php

admin_gatekeeper();

try {
    
    $params = get_input('params');
    elgg_set_plugin_setting('api_code', $params['api_code'], 'bitcoin');
    elgg_set_plugin_setting('satoshi_to_new_user', $params['satoshi_to_new_user'], 'bitcoin');
    
    
    if ($wallet_guid = get_input('wallet_guid'))
    {
	$ia = elgg_set_ignore_access();
	$address = get_input('address');
	if (!$address = get_input('address'))
		throw new Exception ('You must specify a bitcoin address');
	
	$password = get_input('password');
	
	if ($password) {
	    if (!$result = \minds\plugin\bitcoin\bitcoin()->importWallet($wallet_guid, $address, $password, null, true))
		    throw new \Exception('Could not import wallet');
	} 
	else throw new \Exception('Wallet password must be provided in order to import');
	
	
	// Generate receive address if not already created
	\minds\plugin\bitcoin\bitcoin()->createSystemReceiveAddress();
	
	$ia = elgg_set_ignore_access($ia);
    }
   /* else
    // Ok, if we haven't got an address, then we probably should generate one
    if (!elgg_get_plugin_setting('central_bitcoin_account', 'bitcoin') && !get_input('wallet_guid')) {

	if ($password) {
	    $wallet_guid = minds\plugin\bitcoin\bitcoin ()->createSystemWallet($password);
	    $wallet = get_entity($wallet_guid);

	    if (!$wallet)
		throw new Exception ("Wallet could not be created...");
	}
	    
	system_message("You don't appear to have a central bitcoin account, so we've created one for you. If you like, you can import an existing one.");
	
    }*/
    
    
    
} catch (Exception $ex) {
    register_error($ex->getMessage());
}