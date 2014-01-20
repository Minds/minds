<?php
/**
 * Class used to communicate with the cassandra database
 *
 */
 
use phpcassa\ColumnFamily;
use phpcassa\ColumnSlice;
use phpcassa\Connection\ConnectionPool;
use phpcassa\SystemManager;
use phpcassa\Schema\StrategyClass;
use phpcassa\Index\IndexClause;
use phpcassa\Index\IndexExpression;
use phpcassa\Schema\DataType\LongType;
use phpcassa\UUID;

class DatabaseCall{
	
	static $pool;
	static $servers;
	static $keyspace;
	static $cfs;

	public function __construct($cf){
		
		global $CONFIG;
		require_once(dirname(dirname(dirname(__FILE__))) . '/vendors/phpcassa/lib/autoload.php');
		
		$this->servers = $CONFIG->cassandra->servers;
		$this->keyspace = $CONFIG->cassandra->keyspace;
	
		$pool = new ConnectionPool($this->keyspace, $this->servers, null, 1);
		
		$this->pool = $pool;
		
		$this->cf = $this->getCf($cf);
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
	
	public function insert($guid = NULL, array $data = array()){
		if(!$guid){
			$guid = new GUID();
			$guid = $guid->generate();
		}
		//unset guid, we don't want it twice
		unset($data['guid']);
		try{
			$this->cf->insert($guid, $data);
		} catch(Exception $e){
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
		return $this->cf->get_range($offset,"", $limit);
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
		$defaults = array(  'multi' => false,
							'offset' => "",
							'finish' => "",
							'limit' => 200,
							'reversed' => true
							);
		$options = array_merge($defaults, $options);
		$slice = new ColumnSlice($options['offset'], $options['finish'], $options['limit'], $options['reversed']);

		try{
			if($options['multi']){
				return $this->cf->multiget($key, $slice);
			} else {
				return $this->cf->get($key, $slice);
			}
		}catch(Exception $e){
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
		return $this->cf->get_count($key);
	}
	
	/**
	 * Removes a row from a column family
	 * @param int/string $key - the key
	 * @return mixed
	 */
	public function removeRow($key){
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
	 * @return mixed
	 */
	public function removeAttributes($key, array $attributes = array()){
		if(empty($attributes)){
			return false; // don't allow as this will delete the row!
		}
		return $this->cf->remove($key, $attributes);
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

		$sys = new SystemManager($this->servers[0]);
	
		$defaults = array(	"comparator_type" => "UTF8Type",
				"key_validation_class" => 'UTF8Type',
				"default_validation_class" => 'UTF8Type'
				);
		$attrs = array_merge($defaults, $attrs);
	
		$sys->create_column_family($this->keyspace, $name, $attr);
	
		foreach($indexes as $index => $data_type){
	
			$sys->create_index($this->keyspace, $name, $index, $data_type);
	
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
		$obj = new stdClass;

		foreach($array as $k=>$v){
			$obj->$k = $v;
		}
		
		return $obj;
	}
	
}
