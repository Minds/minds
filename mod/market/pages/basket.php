<?php
/**
 * Market basket controller
 */
namespace minds\plugin\market\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\market\entities;

class basket extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		switch($pages[0]){
			case 'add':
				break;
		}
		
		
		
		$body = \elgg_view_layout('one_sidebar', array(
			'content' => $content,
			'sidebar' => elgg_view('market/sidebar'),
			'sidebar_class'=> 'elgg-sidebar-alt'
		));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}
	
}
