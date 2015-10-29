<?php
/**
 * A very quick and easy cache factory
 * @author Mark Harding
 */
namespace Minds\Core\Data\cache;

class apcu extends abstractCacher{

	private $installed = false;
	private $local = [];

	public function __construct(){
		if(function_exists('apc_add'))
			$this->installed = true;
	}

	public function get($key){
		if(isset($this->local[$key]))
			return $this->local[$key];

		if(!$this->installed){
			$this->local[$key] = false;
			return false;
		}

		$value = apc_fetch($key);
		$this->local[$key] = $value;
		return $value;
	}

	public function set($key, $value, $ttl = 0){
		$this->local[$key] = $value;
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
