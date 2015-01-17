<?php
/**
 * Social 
 */

namespace minds\plugin\social\services;

use Minds\Components;
use Minds\Core;

class build extends Components\Plugin{

	static public function build($service){
		$class_name = "minds\\plugin\\social\\services\\$service";
		if(!class_exists($class_name))
			throw new \Exception('Service does not exists');
		
		return new $class_name;
		
	}
			
}
	