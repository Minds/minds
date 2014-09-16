<?php

namespace minds\plugin\payments\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\payments;
use minds\plugin\payments\entities;

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
		
		$forms = elgg_get_entities(array('subtype'=>'taxForm', 'owner_guid'=>elgg_get_logged_in_user_guid()));
		
		$content = elgg_view_form('payments/payouts', array('action'=>'settings/payments/payouts'));
		$content .= elgg_view('payments/tax-details', array('forms'=>$forms));
		
		
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
		
		if(isset($_POST['w9'])){
			
			/**
			 * W9 submitted
			 */
			$w9 = array(
				'form' => 'w9',
				'name' => $_POST['name'],
				'business_name' => isset($_POST['business_name']) ? $_POST['business_name'] : 'n/a',
				'tax_id' => $_POST['tax_id'],
				'tax_type' => $_POST['tax_type'],
				'tax_class' => $_POST['tax_class'],
				'address' => $_POST['address'],
				'city' => $_POST['city'],
				'zip' => $_POST['zip'],
				'signature' => $_POST['signature']
			);
			
			$tax_form = new entities\taxForm($_POST['guid']);
			$tax_form->setEncrypted($w9);
			$tax_form->save();
		}
		
		if(isset($_POST['w8ben'])){
			
			$w8 = array(
				'form' => 'w8ben',
				'name' => $_POST['name'],
				'country' => $_POST['country'],
				'signature' => $_POST['signature']
			);
			
			$tax_form = new entities\taxForm($_POST['guid']);
			$tax_form->setEncrypted($w8);
			$tax_form->save();

		}
		
		/**
		 * We must encrypt all addresses upon writing to the data store
		 */

		
		
		$this->forward('settings/payments/payouts');
	}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    
