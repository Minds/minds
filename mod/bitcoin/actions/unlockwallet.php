<?php

    $wallet_guid = get_input('wallet_guid');
    $password = get_input('password');
    
    try {
	
	if (!$password) throw new \Exception('Password not specified.');
	
	if ($wallet = get_entity($wallet_guid))
	{
	    \minds\plugin\bitcoin\bitcoin()->unlockWallet($wallet_guid, $password);
	    
	    system_message("Wallet unlocked");
	    $return['output'] = 'Wallet unlocked';
	}
	
    } catch (Exception $ex) {
	$return['status'] = -1;
	$return['system_messages']['error'][] = $ex->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($return); exit;