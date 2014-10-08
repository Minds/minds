<?php
/**
 * Payments
 */

namespace minds\plugin\payments;

use minds\bases;
use minds\core;

class start extends bases\plugin{
	
	public function init(){
		
		\elgg_extend_view('css/elgg', 'css/payments');
		\elgg_extend_view('js/elgg', 'js/payments');
		
		/**
		 * Register our page end points
		 */
		$path = "minds\\plugin\\payments\\pages";
		core\router::registerRoutes(array(
				'/settings/payments/methods' => "$path\\methods",
				'/settings/payments/payouts' => "$path\\payouts",
				'/settings/payments/transactions' => "$path\\transactions",
				'/settings/payments/donate' => "$path\\donate",
			));
	
		\elgg_register_event_handler('pagesetup', 'system', array($this, 'pageSetup'));
	
	}
	
	
	/**
	 * Page setup (menus etc)
	 */
	public function pageSetup(){
		if(elgg_get_context() == 'settings'){
			
			\elgg_register_menu_item('page', array(
			    'name' => 'payments:methods',
			    'text' => 'Payment Methods',
			    'href' => 'settings/payments/methods',
			    'title' => elgg_echo('payments:methods')
		    ));
			
			\elgg_register_menu_item('page', array(
			    'name' => 'payments:payouts',
			    'text' => 'Payout Preferences',
			    'href' => 'settings/payments/payouts',
			 ));
			 
			 \elgg_register_menu_item('page', array(
			    'name' => 'payments:transactions',
			    'text' => 'Transactions',
			    'href' => 'settings/payments/transactions',
			 ));
		}
	}
	
	static public function createPayment($details, $amount, $card){
		
		$transaction = new entities\transaction();
		$transaction->amount = $amount;
		$transaction->description = $details;
		$transaction->card = $card;
		$transaction->save(); //save as pending. 
		
		try{
			$paypal_obj= services\paypal::factory()->payment($amount, $currency = 'USD', $details, $card);
		}catch(\Exception $e){
			var_dump($e); exit;
		}
		$transaction->paypal_id = $paypal_obj->getID();
		$transaction->status = 'complete';
		
		self::sendConfirmation(array($transaction->getOwnerEntity(false)->email, 'mark@minds.com', 'bill@minds.com', 'billing@minds.com'), $transaction);
		
		return $transaction->save();
		
	}
	
	static public function sendConfirmation($to, $transaction){
		elgg_set_viewtype('email');
		//\elgg_send_email('mark@minds.com', 'mark@kramnorth.com', 'New Order', '<h1>Thanks for your order..</h1> <p>Your order has been succesfully processed</p>');
		if(core\plugins::isActive('phpmailer')){
			$view = elgg_view('payments/confirmation', array('transaction'=>$transaction));
			\phpmailer_send('mark@minds.com', 'Minds Billing', $to, '', 'Your order: ' . $transaction->guid, $view, NULL, true);
		}
		elgg_set_viewtype('default');
	}
	
}
