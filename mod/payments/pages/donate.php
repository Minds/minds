<?php

namespace minds\plugin\payments\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\payments;
use minds\plugin\payments\entities;

class donate extends core\page implements interfaces\page{
	
	public $context = 'settings';
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		/** 
		 * Set the page owner. Always the same user..
		 */
		elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
		
		
		$content = elgg_view_form('payments/donate', array('action'=>'settings/payments/donate'));
		
		
		$body = \elgg_view_layout('one_sidebar_alt', array('title'=>\elgg_echo('bitcoin:wallet'), 'content'=>$content));
		
		echo $this->render(array('body'=>$body));
		
	}
	
	/**
	 * Accept adding new cards @todo
	 */
	public function post($pages){
		
		//create the transaction
		$transaction = new entities\transaction();
		$transaction->amount = $_POST['amount'];
		
		/**
		 * Figure out which card to use
		 * 
		 * @todo should this be a shared class?
		 */
		$card = NULL;
		$cards = elgg_get_entities(array('subtype'=>'card', 'owner_guid'=>elgg_get_logged_in_user_guid()));
		foreach($cards as $c){
			if($c->card_type == $_POST['card_type'] && substr($c->number, -4) == substr($_POST['number'], -4)){
				$card->card = $c->card_id; //just pass a string
				continue;
			}
		}
		
		if(!$card){
			$card = new entities\card();
			$card_obj = $card->create(array(
				'type' => $_POST['card_type'],
				'number' => (int) $_POST['number'],
				'month' => (int)$_POST['month'],
				'year' => (int) $_POST['year'],
				'sec' => $_POST['sec'],
				'name' => $_POST['name'],
				'name2' => $_POST['name2']
			));
			$card->save();
		}
		
		payments\start::createPayment('Donation', $_POST['amount'], $card->card);
		
		
		$this->forward('settings/payments/payouts');
	}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    
