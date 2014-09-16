<?php

namespace minds\plugin\payments\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\payments;

class methods extends core\page implements interfaces\page{
	
	public $context = 'settings';
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		/** 
		 * Set the page owner. Always the same user..
		 */
		elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
		
		
		$content = elgg_list_entities(array(
				'subtype' => 'card',
				'owner_guid' => elgg_get_logged_in_user_guid(),
				'list_class' => 'vertical-list credit-cards',
				//'prepend' => 'new'
			));
		
		$body = \elgg_view_layout('one_sidebar_alt', array('title'=>\elgg_echo('bitcoin:wallet'), 'content'=>$content));
		
		echo $this->render(array('body'=>$body));
		
	}
	
	/**
	 * Accept adding new cards @todo
	 */
	public function post($pages){}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    
