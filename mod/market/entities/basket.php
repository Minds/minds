<?php 
/**
 * Market basket entity model. Each user has a basket and this is stored in the sessions for a limited amount of time.
 */
 
namespace minds\plugin\market\entities;

use minds\entities;

class basket extends entities\object{
	
	public function addItem($guid, $quantity = 1){
		
	}
	
	public function removeItem($guid, $quantity = 1){
		
	}
	
	public function checkout(){
		// \minds\plugin\market\checkout
		
		//create an order
	}
	
}