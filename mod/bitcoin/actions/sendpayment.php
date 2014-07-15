<?php

try {
    if ($order_guid = get_input('order_guid')) {
	
	$order = get_entity($order_guid);
	
	$amount = bitcoin()->toBTC($order->amount_in_satoshi);
	$address = $order->minds_receive_address;
	
    } else {
	
	$amount = get_input('amount');
	$address = get_input('address');
    }
    
    $password = get_input('wallet_password');
    
    if (!$password) throw new Exception('You need to provide a password to unlock your wallet');
    if (!$amount) throw new Exception('You need to specify the amount to send');
    if (!$address) throw new Exception('You need to specify an address to send to');
    
    $wallet = bitcoin()->getWallet(elgg_get_logged_in_user_entity());
    if (!$wallet) throw new Exception('Could not retrieve your wallet');
    
    // Send payment
    if ($transaction_hash = minds\plugin\bitcoin\bitcoin()->sendPayment($wallet->guid, $address, bitcoin()->toSatoshi($amount))) {
    
	// If this is an order, then update the order details
	if ($order) {
	    $order->last_transaction_hash = $transaction_hash; // Store transaction handler hash
	    
	    $order->save();
	}
    }
    
    forward(elgg_get_site_url().'bitcoin/mywallet');
    
} catch(Exception $e) {
    register_error($e->getMessage());
    error_log('Bitcoin: ' . $e->getMessage());
}