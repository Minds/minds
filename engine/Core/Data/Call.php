<?php
/**
 * Class used to communicate with the cassandra database
 *
 */

namespace Minds\Core\Data;

use phpcassa\ColumnFamily;
use phpcassa\ColumnSlice;
use phpcassa\Connection\ConnectionPool;
use phpcassa\SystemManager;
use phpcassa\Schema\StrategyClass;
use phpcassa\Index\IndexClause;
use phpcassa\Index\IndexExpression;
use phpcassa\Schema\DataType\LongType;
use phpcassa\UUID;

use Minds\Core;
use Minds\Core\config;

class Call extends core\base{

	static $keys = array();	
	static $reads = 0;
	static $writes = 0;
	static $deletes = 0;
	static $counts = 0;
	
	public function __construct($cf = NULL, $keyspace = NULL, $servers = NULL, $sendTimeout = 800, $receiveTimeout = 2000){
		global $CONFIG;
	//	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/vendors/phpcassa/lib/autoload.php');
		
		$this->servers = $servers ?: $CONFIG->cassandra->servers;
		$this->keyspace = $keyspace ?: $CONFIG->cassandra->keyspace;
		
		try{
			$pool = new ConnectionPool($this->keyspace, $this->servers, 1, 2, $sendTimeout, $receiveTimeout);
		
			$this->pool = $pool;
		
			if(isset($cf)){
				$this->cf_name = $cf;
				$this->cf = $this->getCf($cf);
			}
		}catch(\Exception $e){
			
		}
	}
	
	/**
	 * Install the schema
	 * 
	 * @return void
	 */
	public function installSchema(){
		$cfs = array(	
			//'site' => array('site_id' => 'UTF8Type'),
			'plugin' => array('active' => 'IntegerType'),
			//'config' => array(),
			'entities'=> array('type'=>'UTF8Type'),
			'entities_by_time' => array(),
			'user_index_to_guid' => array(),
			'session' => array(),
			'friends' => array(), //@replace with relationships soon
			'friendsof' => array(), //@replace with relationships soon
			'relationships' => array(), //this is a new index for relationships (friends will be merged into here soon)
			//'token' => array('owner_guid'=>'UTF8Type', 'expires' =>'IntegerType' ),
                    	//'log' => array(),
		);
	
		$ks = $this->pool->describe_keyspace();
 
		foreach($cfs as $cf => $indexes){
			$exists = false;
			foreach($ks->cf_defs as $cfdef) {
				if ($cfdef->name == $cf){
					$exists = true;
					break;
				}
			}
			if(!$exists){
				error_log("Installing $cf...\n");
				$this->createCF($cf, $indexes);
			}
		}
        
        $client = Client::build('Cassandra');
        $query = new Cassandra\Prepared\System();
        $client->request($query->createTable("counters", array("guid"=>"varchar", "metric"=>"varchar", "count"=>"counter"), array("guid", "metric")));
	}
	
	/**
	 * Loads the cf interface
	 * @param string $cf - the cf name
	 * !!allowed cfs are 'site', 'session','plugin', 'object', 'user', 'user_index_to_guid', 'widget', 'entities_by_time',
	 * 'notification', 'annotation', 'group', 'friends', 'friendsof', 'timeline','newsfeed','token'!!
	 * @return the cassandra column family interface
	 */
	public function getCf($cf){
		return new ColumnFamily($this->pool, $cf);
	}
	
	public function insert($guid = NULL, array $data = array(), $ttl = NULL){
		if(!$guid){
			$guid = new \GUID();
			$guid = $guid->generate();
		}
		self::$writes++;
		//unset guid, we don't want it twice
		unset($data['guid']);
		try{
			if($ttl)
				$this->cf->insert($guid, $data, null, $ttl);
			else
				$this->cf->insert($guid, $data);
		} catch(\Exception $e){
			error_log('DB Write failure:'.$e->getMessage());
			return false;
		}
		return $guid;
	}
	
	/**
	 * Performs a standard get. NOTE - this will not return ordered content. 
	 * 
	 * @param array $options - a mixed array
	 * @return array - raw data
	 */
	public function get($offset = "", $limit=10){
		self::$reads++;
		return $this->cf->get_range($offset,"", $limit, new ColumnSlice('','',10000));
	}
	
	/**
	 * Performs a query based on indexes. The indexes must be predefined and this function
	 * will not return ordered content. It is recommended to store your own index and query from there
	 * 
	 * This function is good, however, for doing batch processing based on an index value. 
	 * 
	 * @param $expressions - an array of expressions
	 * @return array
	 */
	public function getByIndex(array $expressions = array(), $offset = "", $limit = 10){
		foreach($expressions as $column => $value){
			$index_exps[] = new IndexExpression($column, $value);
		}
		$index_clause = new IndexClause($index_exps, $offset, $limit);
		return $this->cf->get_indexed_slices($index_clause);
	}
	
	/**
	 * Performs a get request for a keys, to be used when an ID is known
	 * 
	 * @param int/string $key - the key (row)
	 * @param array $options - by default contains offset and limit for the row
	 */
	 public function getRow($key, array $options = array()){
	 	self::$reads++;
		array_push(self::$keys, $key);
		$defaults = array(  'multi' => false,
							'offset' => "",
							'finish' => "",
							'limit' => 500,
							'reversed' => true
							);
		$options = array_merge($defaults, $options);
		$slice = new ColumnSlice($options['offset'], $options['finish'], $options['limit'], $options['reversed']);
	
		if(!$this->cf){
			return false;
		}

		try{
			if($options['multi']){
				return $this->cf->multiget($key, $slice);
			} else {
				return $this->cf->get($key, $slice);
			}
		}catch(\Exception $e){
			return false;
		}
	 }
	 
	/**
	 * Performs a get requests for multiple keys
	 * 
	 * @param int/string $key - the key (row)
	 * @param array $options - by default contains offset and limit for the row
	 */
	public function getRows($keys, array $options = array()){
		$options['multi'] = true;
		return $this->getRow($keys, $options);
	}
	
	/**
	 * Count the columns of a row
	 */
	public function countRow($key){
		//return 10; //quick hack until wil figue this out!
		if(!$key)
			return 0;
		try{
			self::$counts++;
			return $this->cf->get_count($key);
		}catch(Exception $e){
			return 0;
		}
	}
	
	/**
	 * Removes a row from a column family
	 * @param int/string $key - the key
	 * @return mixed
	 */
	public function removeRow($key){
		self::$deletes++;
		return $this->cf->remove($key);
	}
	
	/**
	 * Removes multiple rows from a column family
	 * @param array $keys - array of keys to delete
	 * @return array
	 */
	public function removeRows(array $keys){
		foreach($keys as $key){
			$return[$key] = $this->removeRow($key);
		}
		return $return;
	}
	
	/**
	 * Removes attributes (columns) from a row
	 * @param int/string $key - the key
	 * @param array $attributes - the attributes to remove (columns)
	 * @param bool $verify - return a count of true or false? (disable if doing batches as this can slow down)
	 * @return mixed
	 */
	public function removeAttributes($key, array $attributes = array(), $verify= false){
		self::$deletes++;
		if(empty($attributes)){
			return false; // don't allow as this will delete the row!
		}
		if($verify)
			$count = $this->countRow($key);
		$this->cf->remove($key, $attributes);
		//the remove function doens't return a value, so we need to check.. far from ideal..
		if($verify && $this->countRow($key) == $count-count($attributes)){
			return true;
		}

		if(!$verify)
			return;		

		return false;
	}

	/**
	 * Create a column family
	 * 
	 * @param string $name - the name of the column family
	 * @param array $indexes - an array of indexes
	 * @param array $attrs - any specific attributes for the column family to have
	 */
	public function createCF($name, array $indexes = array(), array $attrs = array()){
		global $CONFIG;

		try{
		$sys = new SystemManager($this->servers[0]);
	
		$defaults = array(	"comparator_type" => "UTF8Type",
				"key_validation_class" => 'UTF8Type',
				"default_validation_class" => 'UTF8Type'
				);
		$attrs = array_merge($defaults, $attrs);
	
		$sys->create_column_family($this->keyspace, $name, $attrs);
	
		foreach($indexes as $index => $data_type){
	
			$sys->create_index($this->keyspace, $name, $index, $data_type);
	
		}
		} catch(\Exception $e){
			return false;
		}
	}
	
	/**
	 * Remove a CF
	 * 
	 * !DANGEROUS!
	 */
	public function removeCF(){
		$sys = new SystemManager($this->servers[0]);
		return (bool) $sys->drop_column_family($this->keyspace, $this->cf_name);
	}
	
	/**
	 * Does the keyspace exits
	 * 
	 * @return bool
	 */
	public function keyspaceExists(){
		$exists = false;
		try{
 		       $ks = $this->pool->describe_keyspace();
      			$exists = false;
   			foreach($ks->cf_defs as $cfdef) {
	                	if ($cfdef->name == 'entities_by_time'){
         	         	      $exists = true;
           	       		      break;
             	  		 }
  			}
		}catch(\Exception $e){
                	$exists = false;
        	}
		return $exists;
		if($exists)
			return true;

		
		$sys = new SystemManager($this->servers[0]);
		
		$exists = false;
		
		$ksdefs = $sys->describe_keyspaces();
		foreach ($ksdefs as $ksdef)
        	$exists = $exists || $ksdef->name == $this->keyspace;

   		if ($exists){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Create a keyspace
	 * 
	 * @return bool
	 */
	public function createKeyspace(array $attrs = array()){
		$sys = new SystemManager($this->servers[0]);
		$keyspace = $sys->create_keyspace($this->keyspace, $attrs);
		
		self::__construct(null, $this->keyspace, $this->servers);
		
		return (bool) $keyspace;
	}
	
	/**
	 * Drop keyspace
	 * 
	 * !DANGEROUS... extremely...!
	 * @param bool $confirm - set to true to double check...
	 * 
	 * @return void
	 */
	public function dropKeyspace($confirm = false){
		if(!$confirm){
			return;
		}
		try{
			$sys = new SystemManager($this->servers[0]);
			$sys->drop_keyspace($this->keyspace);
		}catch(\Exception $e){
			//var_dump($e); exit;
			return;
		}
	}
	/**
	 * Create and index for a column family
	 * 
	 * NOTE: This function should be called by a plugin if it needs to query by 'metadata'. 
	 * You should favour a design in which you use your own indexing system though. 
	 */
	public function createIndex(){}
	
	/**
	 * Create an object from an array
	 * 
	 * @param array $array - the array
	 * @return object $object - the object
	 * @todo Make a DB specific object rather than stdClass.
	 */
	public function createObject(array $array = array()){
		$obj = new \stdClass;

		foreach($array as $k=>$v){
			$obj->$k = $v;
		}
		
		return $obj;
	}

	public function stats(){
		return $this->pool->stats();
	}
	
}
