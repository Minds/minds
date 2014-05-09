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
	

	
	public function set($key, $values){
		$this->insert($this->namespace.$name, $data);
	}
	
	public function get($name){
		try{
			return $this->getRow($this->namespace.$name);
		} catch (\Exception $e){
			return false;
		}
	}
	
}
