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
	
	protected $total = 0;
	protected $cookie_id = 'mindsMarketBasket';
	static public $items = array(); //ITEM_GUID => QUANTITY
	
	public function __construct(){
		$this->load();
	}
	
	/**
	 * Load a basket
	 */
	public function load(){
		if(isset($_COOKIE[$this->cookie_id]))
			return self::$items = json_decode($_COOKIE[$this->cookie_id], true);
		
		return false;
	}
	
	/**
	 * Save the basket back to the cookie
	 */
	public function save(){
		return setcookie($this->cookie_id, json_encode(self::$items), time()+360, '/');
	}
	
	/**
	 * Delete the basket (empty)
	 */
	public function delete(){
		self::$items = array();
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
		if(!isset(self::$items[$item->guid]))
			self::$items[$item->guid] = $quantity;
		else 
			self::$items[$item->guid]+$quantity;
	}
	
	/**
	 * Remove an item from the basket
	 * 
	 * @param minds\plugin\market\entities\item $item - the item
	 * @param int $quantity - default to one
	 * @return bool
	 */
	public function removeItem($item, $quantity = 1){
		if(!isset(self::$items[$item->guid]))
			return false;
	
		if(self::$items[$item->guid] == 1)
			unset(self::$items[$item->guid]);
		else
			self::$items[$item->guid]-$quantity;
		
		return true;
	}
	
	/**
	 * Returns a list of items in the basket
	 */
	public function getItems(){
		$items = array();
		foreach($this->getItems() as $item_guid => $quantity){
			$item = new item($item_guid);
			$items[$item] = $quantity;
		}
		return $items;
	}
	
	/*
	 * Calculates the total value of the basket
	 */
	public function calculateTotal(){
		foreach($this->getItems() as $item => $quantity)
			$this->total += $item->price * $quantity;
		
		return $this->total;
	}
	
	public function checkout(){
		
		//create an order
		$order = new order();
		$order->items = $this->getItems();
		$order->total = $this->calculateTotal();
		
		$this->delete();
	}
	
	
}