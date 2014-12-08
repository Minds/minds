<?php
/**
 * A very quick and easy cache factory
 * @author Mark Harding
 */
namespace minds\core\data\cache;

abstract class abstractCacher{

	abstract public function get($key);
	
	abstract public function set($key, $value);
	
	abstract public function destroy($key);
	
}
