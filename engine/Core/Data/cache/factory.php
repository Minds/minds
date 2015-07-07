<?php
/**
 * A very quick and easy cache factory
 * @author Mark Harding
 */
namespace Minds\Core\Data\cache;

class factory{

	static private $default  = 'Redis';
	
	/**
	 * Build the cacher
	 * @param $cacher - the cacher to use
	 * @throws EXCEPTION
	 * @return cacher object
	 */
	static public function build($cacher = NULL){
		if(!$cacher)
			$cacher = self::$default;
        
        if(!class_exists('\Redis') && $cacher = "Redis")
            $cacher = "apcu";

		$cacher = "\\Minds\\Core\\Data\\cache\\$cacher";
		if(class_exists($cacher)){
			return new $cacher();
		}
		
		throw new \Exception('Cacher not found ' . $cacher);
	}
	
}
