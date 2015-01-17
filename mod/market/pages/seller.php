<?php
/**
 * Market basket controller
 */
namespace minds\plugin\market\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\market\entities;

class seller extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		switch($pages[0]){
			case 'orders':
				$guids = core\data\indexes::fetch('object:market_order:seller:'.elgg_get_logged_in_user_guid(), array('limit'=> 12));
				if(!$guids){
					$content = ' ';
					break;
				}
				$content = core\entities::view(array('guids'=>$guids, 'full_view'=>false, 'list_class'=>'minds-market-order-items', 'masonry'=>false));
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
