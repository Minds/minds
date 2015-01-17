<?php
/**
 * Market order entity model. 
 */
 
namespace minds\plugin\market\entities;

use minds\entities;
use minds\plugin\market\entities\basket;
use Minds\Core;

class order extends entities\object{

	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'subtype' => 'market_order',
			'owner_guid'=>elgg_get_logged_in_user_guid(),
			'access_id' => 0, //private
		));
	}
	
	/**
	 * Set the status
	 * @param string $status - eg. complete
	 * @return $this
	 */
	public function setStatus($status){
		$this->status = $status;
		return $this;
	}
	
	/**
	 * Set the relative transaction id
	 * @param numeric $id
	 * @return $this
	 */
	public function setTransactionID($id){
		$this->transaction_id = $id;
		return $this;
	}
	
	/**
	 * Set the order item
	 * @param object $item
	 * @return $this
	 */
	public function setItem($item){
		$this->item = $item;
		$this->setTotal($item['price'] * $item['quantity']);
		return $this;
	}
	
	/**
	 * Set the total of the order
	 * @param int $total
	 * @return $this
	 */
	public function setTotal($total){
		$this->total = $total;
		return $this;
	}
	
	/**
	 * Return the url
	 */
	public function getURL(){
		return elgg_get_site_url() . 'market/orders/'.$this->guid;
	}
	
	/**
	 * The indexes...
	 */
	public function getIndexKeys($ia = false){
		$seller = $this->item['owner_guid'];
		$indexes = array_merge(parent::getIndexKeys($ia), array(
			"object:market_order:seller:$seller"
		));
		return $indexes;
	}
	
	public function getSellerEntity($brief = true){
		if($this->item['owner'] && $brief)
			return new \minds\entities\user($this->item['owner']);
		
		return new \minds\entities\user($this->item['owner_guid']);
	}
}