<?php
/**
 * Minds main class
 */
namespace minds\core;

class multisite extends base{

	protected $domain;	

	public function __construct($domain = NULL){
		global $DOMAIN;

		//load settings, incase we dont have them
		require_once(__MINDS_ROOT__ . '/engine/multi.settings.php');

		if(!$DOMAIN && isset($_SERVER['HTTP_HOST']))
			$this->domain = $_SERVER['HTTP_HOST'];
		elseif($DOMAIN)
			$this->domain = $DOMAIN;

		if($this->domain)
			$this->load($this->domain);
	}

	public function load($domain){
		global $CONFIG;
		if(!$row = $this->getCache($domain) ){
			$db = new data\call('domain', $CONFIG->multisite->keyspace, $CONFIG->multisite->servers);
               		$row = $db->getRow($domain);
			$this->saveCache($domain, $row);
		}

                if(!$row['installed'] && !defined('__MINDS_INSTALLING__')){
                       header("Location: install.php"); 
                       exit; 
                }
		
		$keyspace = @unserialize($row['keyspace']) ? unserialize($row['keyspace'])  : $row['keyspace'];
		$CONFIG->cassandra = new \stdClass();
		$CONFIG->cassandra->keyspace =$keyspace;
		$CONFIG->cassandra->servers =  $CONFIG->multisite->servers;
		$CONFIG->wwwroot = "http://$domain/"; 
        	$CONFIG->dataroot = "/data/".$keyspace;
	}

	public function getCache($domain){
		//check the tmp directory to see if there is a cached config of the site
		$path = "/tmp/nodes/$domain";
		if(file_exists($path)){
			return json_decode(file_get_contents($path), true);
		}
		return false;
	}

	public function saveCache($domain, $data){
		$path = "/tmp/nodes/$domain";
		@mkdir('/tmp/nodes/');
		file_put_contents($path, json_encode($data));
	}

	public function getKeyspace($domain = NULL){
		global $CONFIG;
		$db = new data\call('domain', $CONFIG->multisite->keyspace, $CONFIG->multisite->servers);
		$row = $db->getRow($domain);
		return $keyspace = unserialize($row['keyspace']);
	}
	
}
