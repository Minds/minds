<?php
/**
 * A very quick and easy cache factory
 * @author Mark Harding
 */
namespace Minds\Core\Data\cache;

class factory{

	static private $default  = 'Redis';
	static private $cachers = [];

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

		if(isset(self::$cachers[$cacher]))
			return self::$cachers[$cacher];

		$cacher_class = "\\Minds\\Core\\Data\\cache\\$cacher";
		if(class_exists($cacher_class)){
			self::$cachers[$cacher] = new $cacher_class();
			return self::$cachers[$cacher];
		}

		throw new \Exception('Cacher not found ' . $cacher);
	}

}
