<?php
/**
 * Minds cluster entity
 */

namespace minds\entities;

use minds\core\data;

class cluster extends entity{
	
	public $ttl = 1800; //keep nodes valid for half an hour
	
	public function __construct($guid = NULL){
		$this->cluster = 'master';
	}
	
	public function getNodes($limit=10000){
		$db = new data\call('user_index_to_guid');
		return $db->getRow('clusters:'.$this->cluster);
	}
	
	/**
	 * Stores the other nodes in the lookup column family
	 * 
	 * @param string $server_addr - 
	 */
	public function join($server_addr){
		$db = new data\call('user_index_to_guid');
		$row = $db->insert('clusters:'.$this->cluster, array($server_addr=>time()), $this->ttl);
		return $this->getNodes();
	}
	
	public function leave(){
		
	}
	
}
