<?php
/**
 * A very quick and easy cache factory
 * @author Mark Harding
 */
namespace minds\core\data\cache;

class apcu extends abstractCacher{
	
	private $installed = false;
	
	public function __construct(){
		if(function_exists('apc_add'))
			$this->installed = true;
	}
	
	public function get($key){
		if(!$this->installed)
			return false;
		
		return apc_fetch($key);
	}
	
	public function set($key, $value){
		if(!$this->installed)
			return $this;
		
		apc_store($key, $value);
		return $this;
	}
	
	public function destroy($key){
		if(!$this->installed)
			return false;
		
		apc_delete($key);
		return $this;
	}
}
	