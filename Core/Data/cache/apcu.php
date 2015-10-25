<?php
/**
 * A very quick and easy cache factory
 * @author Mark Harding
 */
namespace Minds\Core\Data\cache;

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
	
	public function set($key, $value, $ttl = 0){
		if(!$this->installed)
			return $this;
		
		apc_store($key, $value, $ttl);
		return $this;
	}
	
	public function destroy($key){
		if(!$this->installed)
			return false;
		
		apc_delete($key);
		return $this;
	}
}
	
