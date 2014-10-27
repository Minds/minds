<?php 
/**
 * Market basket entity model. The basket is stored in a cookie, so that we can use for logged out users also.
 * 
 * Why not sessions? On caching pages, we remove the minds sessions cookie for security reasons. 
 * We don't want a session bleed to occur and someone accessing someone elses account.  
 */
 
namespace minds\plugin\market\entities;

use minds\entities;

class basket extends entities\entity{
	
	protected $cookie_id = 'mindsMarketBasket';
	public $items = array(); //ITEM_GUID => QUANTITY
	
	public function __construct(){
		$this->load();
	}
	
	/**
	 * Load a basket
	 */
	public function load(){
		if(isset($_COOKIE[$this->cookie_id]))
			return $this->items = json_decode($_COOKIE[$this->cookie_id], true);
		
		return false;
	}
	
	/**
	 * Save the basket back to the cookie
	 */
	public function save(){
		return setcookie($this->cookie_id, json_encode($this->items), time()+360, '/');
	}
	
	/**
	 * Delete the basket (empty)
	 */
	public function delete(){
		$this->items = array();
		return setcookie($this->cookie_id, '', time()-360, '/');
	}
	
	/**
	 * Add an item to the users basket
	 * 
	 * @param minds\plugin\market\entities\item $item - the item
	 * @param int $quantity - default to one
	 * @return void
	 */
	public function addItem($item, $quantity = 1){
		if(!isset($this->items[$item->guid]))
			$this->items[$item->guid] = array('quantity'=>$quantity, 'price'=>$item->price);
		else 
			$this->items[$item->guid]['quantity'] = $this->items[$item->guid]['quantity'] + $quantity;
		
		return $this;
	}
	
	/**
	 * Remove an item from the basket
	 * 
	 * @param minds\plugin\market\entities\item $item - the item
	 * @param int $quantity - default to one
	 * @return bool
	 */
	public function removeItem($item, $quantity = 1){
		if(!isset($this->items[$item->guid]))
			return false;
	
		if(self::$items[$item->guid] == 1)
			unset($this->items[$item->guid]);
		else
			$this->items[$item->guid]-$quantity;
		
		return true;
	}
	
	/**
	 * Returns a list of items in the basket
	 */
	public function getItems(){
		return $this->items;
	}
	
	/**
	 * Count the items, inluding the quantity for each
	 */
	public function countItems(){
		$count = 0; 
		foreach($this->items as $guid => $data)
			$count = $count + $data['quantity'];
		
		return $count;
	}
	
	/**
	 * Return the total of the basket
	 * WARNING: DO NOT USE THIS VALUE FOR CHECKOUT, it is only as an indicator to the user
	 * @return float
	 */
	public function total(){
		$this->total = 0;
		foreach($this->items as $guid => $data)
			$this->total = $this->total + ($data['price'] *  $data['quantity']);
		
		return (float) $this->total;
	}
	
	public function checkout(){
		
		//create an order
		$order = new order();
		$order->items = $this->getItems();
		$order->total = $this->calculateTotal();
		
		$this->delete();
	}
	
	
}