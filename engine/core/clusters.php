<?php
/**
 * Minds clusters controller
 */
namespace minds\core;

class clusters extends base{
	
	public $seeds = array('localhost');
	
		
	/**
	 * Init
	 */
	public function init(){
		
		$path = "minds\\pages\\clusters";
		router::registerRoutes(array(
			"/api/v1/cluster" => "$path\\index",
		));
	}
	
	/**
	 * Call
	 * 
	 * @description Vital for inter-node communications
	 */
	public function call($method, $address, $endpoint, $params){
		
	}
	
	public function joinCluster($cluster, $server_uri){
		//notify everyone in the cluster
	}
		
}