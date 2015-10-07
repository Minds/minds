<?php

namespace minds\plugin\bitcoin\pages;

use Minds\Core;
use minds\interfaces;
//use minds\plugin\comments;
use minds\plugin\bitcoin\entities;

class receive extends core\page implements Interfaces\page {

    /**
     * Get requests
     */
    public function get($pages) {
	try {
	    error_log('BTC CALLBACK: ' . $pages);
	    $ia = \elgg_set_ignore_access(true);

	    $wallet = new entities\wallet($pages[0]);

	    $transaction = new entities\transactions(array(
		'action' => 'receive',
		'from_address' => $_GET['input_address'],
		'owner_guid' => $wallet->owner_guid,
		'amount' => $_GET['value']
	    ));
	    $transaction->save();


	    elgg_trigger_plugin_hook('payment-received', 'blockchain', array('transaction_guid' => $transaction, 'wallet_guid' => $pages[0]));
	} catch (\Exception $e) {
	    error_log('BTC CALLBACK ERROR: ' . $e->getMessage());
	    register_error($e->getMessage());
	}

	elgg_set_ignore_access($ia);
    }

    public function post($pages) {
	
    }

    public function put($pages) {
	
    }

    public function delete($pages) {
	
    }

}
