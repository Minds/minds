<?php
/**
 * Market orders controller (buyer)
 */
namespace minds\plugin\market\pages;

use Minds\Core;
use Minds\Interfaces;
use minds\plugin\market\entities;

class orders extends core\page implements Interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		if(isset($pages[0]) && is_numeric($pages[0])){
			$order = new entities\order($pages[0]);
			$content = elgg_view_entity($order, array('full_view'=>true));
		} else {
			$content = core\Entities::view(array('subtype'=>'market_order', 'owner_guid'=>elgg_get_logged_in_user_guid(), 'full_view'=>false, 'list_class'=>'minds-market-order-items', 'masonry'=>false));
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
