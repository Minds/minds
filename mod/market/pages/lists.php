<?php
/**
 * Market lists
 */
namespace minds\plugin\market\pages;

use minds\core;
use minds\interfaces;

class lists extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		switch($pages){
			case 'owner':
				$content = 'This is the owner';
			case 'all':
			default:
				$content = 'This is the all page';
		}
		
		$body = \elgg_view_layout('one_column', array('content'=>$content));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}
	
}
