<?php

namespace minds\plugin\payments\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\payments;

class payouts extends core\page implements interfaces\page{
	
	public $context = 'settings';
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		/** 
		 * Set the page owner. Always the same user..
		 */
		elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
		
		$content = elgg_view_form('payments/payouts', array('action'=>'settings/payments/payouts'));
		
		
		$body = \elgg_view_layout('one_sidebar_alt', array('title'=>\elgg_echo('bitcoin:wallet'), 'content'=>$content));
		
		echo $this->render(array('body'=>$body));
		
	}
	
	/**
	 * Accept adding new cards @todo
	 */
	public function post($pages){
		
		if(isset($_POST['paypal_email'])){
			
			\elgg_set_plugin_user_setting('paypal_address', $_POST['paypal_email'], \elgg_get_logged_in_user_guid(), 'payments');
			
		}
		
		/**
		 * We must encrypt all addresses upon writing to the data store
		 */
		
		$encrypt = new core\encrypt('d0a7e7997b6d5fcd55f4b5c32611b87cd923e88837b63bf2941ef819dc8ca282');
		$string = 'testing this encrypted string 123abc';
		$e = $encrypt->encrypt($string);
		var_dump($encrypt->decrypt($e)); exit;
		
		
		$this->forward('settings/payments/payouts');
	}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    
