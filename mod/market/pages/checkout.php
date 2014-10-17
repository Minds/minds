<?php
/**
 * The checkout controller
 */
namespace minds\plugin\market\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\market\entities;
use minds\plugin\payments;

class checkout extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		$basket = new entities\basket();
		$guids = array_keys($basket->items);
		if(!$guids){
			$this->forward(REFERRER);
		}
		
		//show the payments form
		$content = elgg_view('input/card');
		
		/*if($guids)
			$content = core\entities::view(array('guids'=>$guids, 'pagination'=>false));
		else
			$content = '';*/
		
		$body = \elgg_view_layout('one_sidebar', array(
			'content' => $content,
			'sidebar' => elgg_view('market/sidebar'),
			'sidebar_class'=> 'elgg-sidebar-alt'
		));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){
		
		switch($pages[0]){
			case 'payment':
				$card = new payments\entities\card();
				$c = $card->create(array(
						'type' => $_POST['type'],
						'number' => $_POST['number'],
						'month' => $_POST['month'],
						'year' => $_POST['year'],
						'sec' => $_POST['sec'],
						'name' => $_POST['name'],
						'name2' => $_POST['name2']
					));
				payments\start::createPayment($details, $amount, $c);
				break;
			case 'confirm':
				break;
		}
		
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}
	
}
