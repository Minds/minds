<?php

abstract class MindsAnalyticsService{
	
	public function __construct(){
		$this->connect();
	}
	
	abstract public function connect();
	
	abstract public function fetch(array $options = array());
	
	abstract public function render($results);
	
	/**
	 * Return the guid of from a url
	 * 
	 * @param string url - the minds url
	 * @return int guid
	 */
	public function getGuidFromUrl($url){
		$g = new GUID();
		$segments = explode( '/', $url);
		if($guid = intval($segments[3])){
			return $g->migrate($guid);
		} else {
			return 0;
		}
	}
}

