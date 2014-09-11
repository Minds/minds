<?php

namespace minds\plugin\bitcoin\pages;

use minds\core;
use minds\interfaces;
//use minds\plugin\comments;
use minds\plugin\bitcoin\entities;

class receive extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		$ia = \elgg_set_ignore_access(true);
		
		$wallet = new entities\wallet($pages[0]);
		
		$transaction = new entities\transactions(array(
			'action' => 'receive',
			
			'from_address' => $_GET['input_address'],
			'owner_guid' => $wallet->owner_guid,
			'amount' => $_GET['value']
		));
		$transaction->save();
		
		
		elgg_trigger_plugin_hook('payment-received', 'blockchain', array('transaction_guid'=>$transaction, 'wallet_guid'=>$pages[0]));
		
		elgg_set_ignore_access($ia);
	}
	

	public function post($pages){}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    