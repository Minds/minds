<?php
/**
 * Helper wrapper for lookup
 *
 */

namespace minds\core\data;

use minds\core;

class lookup extends call{

	private $namespace = '';
	
	public function __construct($namespace = NULL){
		parent::__construct('user_index_to_guid');
		
		if($namespace)
			$this->setNamespace($namespace);
	}
	
	public function setNamespace($namespace){
		$this->namespace = $namespace . ':';
	}
	
	public function set($key, $values){
		if(!is_array($values))
			$values = array($values);
		return $this->insert($this->namespace.$key, $values);
	}
	
	public function remove($key){
		return $this->removeRow($this->namespace.$key);
	}
	
	public function get($name){
		try{
			return $this->getRow($this->namespace.$name);
		} catch (\Exception $e){
			return false;
		}
	}
	
}
