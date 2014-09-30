<?php
/**
 * The indexes helper function
 *
 */

namespace minds\core\data;

use minds\core;

class indexes extends call{

	private $namespace = '';
	
	public function __construct($namespace = NULL){
		parent::__construct('entities_by_time');
		
		if($namespace)
			$this->setNamespace($namespace);
	}
	
	public function setNamespace($namespace){
		$this->namespace = $namespace . ':';
	}
	
	public function set($name, array $guids = array()){
		return $this->insert($this->namespace.$name, $guids);
	}
	
		/**
	 * Get from the index (returns uuids)
	 * 
	 * @param string/int $key_id - the ID for the row to return
	 * @param array $options - limit, offset, reversed
	 */
	public function get($key_id, array $options = array('limit'=>12, 'offset'=>'','reversed'=>true)){
		try{
			return $this->getRow($this->namespace.$key_id, $options);
		} catch (\Exception $e){
			return false;
		}
	}
	
	public function remove($key, array $guids = array()){
		$this->removeAttributes($key, $guids);
	}
	
	/**
	 * Static methods
	 */
	 static public function fetch($key, array $options = array('limit'=>12, 'offset'=>'','reversed'=>true)){
	 	
	 	$db = new call('entities_by_time');
		try{
			return $db->getRow($key, $options);
		} catch (\Exception $e){
			return false;
		}
		
	 }
	
}
