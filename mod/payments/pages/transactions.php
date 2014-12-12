<?php

namespace minds\plugin\payments\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\payments;
use minds\plugin\payments\entities;

class transactions extends core\page implements interfaces\page{
	
	public $context = 'settings';
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		/** 
		 * Set the page owner. Always the same user..
		 */
		elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
		
		$transactions = elgg_get_entities(array(
				'subtype' => 'transaction',
				'owner_guid' => elgg_get_logged_in_user_guid(),
				'list_class' => 'vertical-list credit-cards',
			));
		if($transactions){
			$content = elgg_view_entity_list($transactions, array('list_class'=>'vertical-list credit-cards'));
		} else {
			$content = 'No transaction history';
		}
		
		$body = \elgg_view_layout('one_sidebar_alt', array('title'=>\elgg_echo('bitcoin:wallet'), 'content'=>$content));
		
		echo $this->render(array('body'=>$body));
		
	}
	
	/**
	 * Accept adding new cards @todo
	 */
	public function post($pages){
	}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    
