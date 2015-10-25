<?php
/**
 * A very quick and easy cache factory
 * @author Mark Harding
 */
namespace Minds\Core\Data\cache;

abstract class abstractCacher{

	abstract public function get($key);
	
	abstract public function set($key, $value, $ttl = 0);
	
	abstract public function destroy($key);
	
}
