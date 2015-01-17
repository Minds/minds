<?php
/**
 * The checkout controller
 */
namespace minds\plugin\market\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\market\entities;
use minds\plugin\market\notifications;
use minds\plugin\payments;

class checkout extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		
		if($pages[0] == 'success'){
			
			$content = 'Your order has been sucessfully placed. Your transaction reference is '.$pages[1];
			
		} else {
		
			$basket = new entities\basket();
			$guids = array_keys($basket->items);
			if(!$guids){
				$this->forward(REFERRER);
			}
			
			//show the payments form
			$content = elgg_view_form('market/checkout', array('action'=>elgg_get_site_url() . 'market/checkout/payment'));
		
		}

		
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
			
				/**
				 * Gather the accurate basket items. Prevents forgery of the cookies.
				 */
				$basket = new entities\basket();
				$guids = array_keys($basket->items);
				$items = array();
				$total = 0;
				foreach(core\entities::get(array('guids'=>$guids)) as $item){
					$items[$item->guid] = array_merge($item->export(), array('quantity'=>$basket->items[$item->guid]['quantity']));
					$total = $total + ($items[$item->guid]['price'] * $items[$item->guid]['quantity']);
				}
				
				$card = new payments\entities\card();
				$c = $card->create(array(
						'type' => $_POST['card_type'],
						'number' => (int) $_POST['number'],
						'month' => $_POST['month'],
						'year' => $_POST['year'],
						'sec' => $_POST['sec'],
						'name' => $_POST['name'],
						'name2' => $_POST['name2']
					));

				if(!$c)
					return $this->forward(REFERRER);
				

				//configure the details
				$transaction_id = payments\start::createPayment("Order from the market", $total, $c->id);
				$transaction = new payments\entities\transaction($transaction_id);
				
				/**
				 * Create an order for each item
				 */
				foreach($items as $item){
					
					$order = new entities\order();
					$order->setItem($item)
						->setTransactionID($transaction_id)
						->save();
						
					$transaction->orders[] = $order->guid;
					
					notifications::sendToBuyer($order->getOwnerEntity(false), 'Your order: '.$order->guid, elgg_view('market/emails/buyer', array('order'=>$order)));
					notifications::sendToSeller($order->getSellerEntity(), 'New Order: '.$order->guid, elgg_view('market/emails/seller', array('order'=>$order)));
					
				}
				
				$transaction->save();
				
				//empty the basket
				$basket->delete();
				
				
				$this->forward('market/checkout/success/'.$transaction_id);
				break;
		}
		
	}
	
	public function put($pages){
		
	}
	
	public function delete($pages){
		
	}
	
	
	//private function calculateTotal
}
