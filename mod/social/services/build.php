<?php
/**
 * Social 
 */

namespace minds\plugin\social\services;

use Minds\Components;
use Minds\Core;

class build extends Components\Plugin{

	static public function build($service, $params = array()){
		$class_name = "minds\\plugin\\social\\services\\$service";
		if(!class_exists($class_name))
			throw new \Exception('Service does not exists');
		
		$class = new $class_name;
        if(isset($params['access_token']))
            $class->access_token = $params['access_token'];
        return $class;
	}
			
}
	
