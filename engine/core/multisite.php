<?php
/**
 * Minds main class
 */
namespace minds\core;

class multisite extends base{

	protected $domain;	

	public function __construct($domain = NULL){
		global $DOMAIN;

		if(!$DOMAIN && isset($_SERVER['HTTP_HOST']))
			$this->domain = $_SERVER['HTTP_HOST'];
		elseif($DOMAIN)
			$this->domain = $DOMAIN;

		if($this->domain)
			$this->load($this->domain);
	}

	public function load($domain){
		global $CONFIG; 
		$db = new data\call('domain', $CONFIG->multisite->keyspace, $CONFIG->multisite->servers);
                $row = $db->getRow($domain);

		$CONFIG->cassandra->keyspace = unserialize($row['keyspace']);
		$CONFIG->wwwroot = unserialize($row['wwwroot']);
        	$CONFIG->dataroot = unserialize($row['dataroot']);
	}


	public function getKeyspace($domain = NULL){
		global $CONFIG;
		$db = new data\call('domain', $CONFIG->multisite->keyspace, $CONFIG->multisite->servers);
		$row = $db->getRow($domain);
		return $keyspace = unserialize($row['keyspace']);
	}
	
}
