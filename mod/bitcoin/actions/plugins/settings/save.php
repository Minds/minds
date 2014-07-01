<?php

admin_gatekeeper();

try {
    
    if ($wallet_guid = get_input('wallet_guid'))
    {
	$address = get_input('address');
	//if (!$address = get_input('address'))
	//	throw new Exception ('You must specify a bitcoin address');
	
	$password = get_input('password');
	
	if (!$result = \minds\plugin\bitcoin\bitcoin()->importWallet($wallet_guid, $address, $password, true))
		throw new \Exception('Could not import wallet');
	
	
    }
    
} catch (Exception $ex) {
    register_error($ex->getMessage());
}