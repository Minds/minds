<?php
/**
 * Simple page tokens 
 */
namespace minds\core;

class token{

	public static function generate($uri=NULL, $ts=NULL){
		if(!$uri)
			$uri = $_SERVER['REQUEST_URI'];
		if(!$ts)
			$ts = time();

		return sha1($uri . $ts . \get_site_secret());
	}

	public static function validate($uri = NULL, $ts = NULL, $token=NULL){
		if(!$uri){	
			$uri = explode('?',$_SERVER['REQUEST_URI']);
			$uri = $uri[0];
		}
		if(!$ts)
			$ts = \get_input('__elgg_ts');
		if(!$token)
			$token = \get_input('__elgg_token');

		if(self::generate($uri, $ts) == $token)
			return true;
		
		return false;
	}
	
}
