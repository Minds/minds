<?php
/**
 * Minds cluster entity
 */

namespace Minds\Entities;

use Minds\Core\data;

class Cluster extends Entity{

	public $ttl = 1800; //keep nodes valid for half an hour

	public function __construct($guid = NULL){
		$this->cluster = 'master';
	}

	public function getNodes($limit=10000){
		$db = new Data\Call('user_index_to_guid');
		$row = $db->getRow('clusters:'.$this->cluster);
		$row[elgg_get_site_url()] = time(); //must always return ourself
		return $row;
	}

	/**
	 * Stores the other nodes in the lookup column family
	 *
	 * @param string $server_addr -
	 */
	public function join($server_addr){
		$db = new Data\Call('user_index_to_guid');
		$row = $db->insert('clusters:'.$this->cluster, array($server_addr=>time()), $this->ttl);
		return $this->getNodes();
	}

	public function leave(){

	}

}
