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
	
	public function set($name, array $uuids = array()){
		$this->insert($this->namespace.$name, $uuids);
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
	
}
