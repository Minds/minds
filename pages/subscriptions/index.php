<?php
/**
 * Minds subscriptions pages
 */
namespace minds\pages\subscriptions;

use minds\core;
use minds\interfaces;
use minds\entities;

class index extends core\page implements interfaces\page{
	
	public function get($pages){
		
		$body = \elgg_view_layout('tiles', array(
			'title'=>\elgg_echo('subscriptions'), 
			'content'=>$content, 
'filter_override' => elgg_view('channels/nav', array('selected' => $vars['page'])),			
		));
		
		echo $this->render(array('body'=>$body));
		
	}
	
	public function post($pages){
		
		
	}
	
	public function put($pages){
		throw new \Exception('Sorry, the put method is not supported for the page');
	}
	
	public function delete($pages){
		throw new \Exception('Sorry, the delete method is not supported for the page');
	}
	
}
