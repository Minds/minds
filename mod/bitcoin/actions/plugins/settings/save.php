<?php
admin_gatekeeper();

try {
    
    $params = get_input('params');
    elgg_set_plugin_setting('api_code', $params['api_code'], 'bitcoin');
    elgg_set_plugin_setting('satoshi_to_new_user', $params['satoshi_to_new_user'], 'bitcoin');
    
	
	if($_POST['generate']){
		minds\plugin\bitcoin\services\blockchain::createSystemWallet($_POST['password']);
	}
    
    /*if ($wallet_guid = get_input('wallet_guid')){
		$ia = elgg_set_ignore_access();
		$address = get_input('address');
		if (!$address = get_input('address'))
			throw new Exception ('You must specify a bitcoin address');
	
		$password = get_input('password');
		if ($password) {
		    if (!$result = \minds\plugin\bitcoin\bitcoin()->importWallet($wallet_guid, $address, $password, null, true))
			    throw new \Exception('Could not import wallet');
		} else {
			throw new \Exception('Wallet password must be provided in order to import');
		}
	
	
		// Generate receive address if not already created
		\minds\plugin\bitcoin\bitcoin()->createSystemReceiveAddress();
		
		$ia = elgg_set_ignore_access($ia);
	}*/
    
} catch (\Exception $ex) {
    register_error($ex->getMessage());
}