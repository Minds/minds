<?php
/**
 * A basic elasticsearch service controller
 */

namespace minds\plugin\search\services;

class elasticsearch{
	
	protected $server_addr;
	
	public function __construct(){
		$this->server_addr = \elgg_get_plugin_setting('server_addr','search')?:'localhost';
	}
	
	/**
	 * Call elasticsearch
	 */
	public function call($endpoint, $method = 'GET', $data = array()){
			
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->server_addr . '/' . $endpoint);
		curl_setopt($ch, CURLOPT_PORT, 9200);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		//curl_setopt($ch,CURLOPT_TIMEOUT_MS, 500);
		if(!empty($data))
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$result = curl_exec($ch);
		curl_close($ch);

		return json_decode($result,true);
	}
	
	/**
	 * Query elasticsearch
	 */
	public function query($type = NULL, $query = NULL, $sort = '', $limit = 24, $offset=0){
		 return $this->call(
		 	$type . '/_search?' . http_build_query(array('q' => $query, 'sort'=>$sort,'size'=> $limit, 'from'=> $offset)), 
		 	'GET'
		);
	}
	
	/**
	 * Create an index
	 */
	function create($index){
		$this->call($index, 'PUT');
	}
	
	/**
	 * Add an item
	 */
	function add($type, $id, $data){
		if(!$type)
			throw new \Exception('You must specift a type');
		
		 $result = $this->call($type . '/'. $id, 'PUT', $data);
		 
		 //if(!$result['created'])
		 //	throw new \Exception('An unknown error occured in creating this document');
		 
		 return $result;
	}
}
