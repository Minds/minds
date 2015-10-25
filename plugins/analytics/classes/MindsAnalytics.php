<?php

class MindsAnalytics{
	
	static $result;
	
	/**
	 * @param string $service - the service (default = google analytics)
	 */
	public function __construct($service = 'google'){
		$this->service = $this->getService($service);
	}
	
	/**
	 * Load the service interface
	 * 
	 * @param string $service - the service name
	 */
	public function getService($service){
		$service = ucfirst($service);
		$class = "MindsAnalyticsService$service";
		return new $class();
	}
	
	/**
	 * Fetch from the analytics service
	 * 
	 * @param array $options 
	 */
	public function fetch(array $options = array()){
		
		$defaults = array(
				'from'=>  date('o-m-d', time() - 60 * 60 * 24), //yesterday
				'to'=>date('o-m-d', time()),//today
				'limit'=>10000
				);
				
		$options = array_merge($defaults, $options);
		self::$result = $this->service->fetch($options);
		return self::$result;
	}
	
}
