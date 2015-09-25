<?php
/**
 * Market basket controller
 */
namespace minds\plugin\market\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\market\entities;

class basket extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		$basket = new entities\basket();
		
		switch($pages[0]){
			case 'add':
				$item = new entities\item($pages[1]);
				$basket->addItem($item, 1)
					->save();
					
				$this->forward('market/basket');
				break;
		}
		
		$guids = array_keys($basket->items);
		if($guids){
			$items = core\Entities::get(array('guids'=>$guids, 'pagination'=>false));
			foreach($items as $k => $item){
				$items[$k]->quantity = $basket->items[$item->guid]['quantity'];
			}
			$content = elgg_view('market/basket', array('items'=>$items));
		} else
			$content = '';
		
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
