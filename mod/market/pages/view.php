<?php
/**
 * Market view page
 */
namespace minds\plugin\market\pages;

use minds\core;
use minds\interfaces;

class view extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		$body = \elgg_view_layout('content', array());
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}
	
}
