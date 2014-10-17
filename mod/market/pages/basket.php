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
		if($guids)
			$content = core\entities::view(array('guids'=>$guids, 'pagination'=>false));
		else
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
