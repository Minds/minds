<?php

// prep core classes to be autoloadable
spl_autoload_register('_minds_autoload');
//elgg_register_classes(dirname(__FILE__) . '/legacy/classes');
elgg_register_classes(dirname(__FILE__) . '/classes');

/**
 * Autoload classes
 *
 * @param string $class The name of the class
 *
 * @return void
 * @throws Exception
 * @access private
 */
function _minds_autoload($class) {
	global $CONFIG;

	//support the legacy naming conventions
	if(isset($CONFIG->classes[$class]) || strpos($class, 'Elgg') !== FALSE){
		if(!include($CONFIG->classes[$class])){
		//	return false; //maybe return an error?
		} else {
			return true;
		}
	}

	$file = dirname(dirname(__FILE__)) . '/'. preg_replace('/minds/', 'engine', str_replace('\\', '/', $class),1) . '.php'; 
	//echo $file; 
	if(file_exists($file)){
    	require_once $file;
		return true;
	}

	//plugins follow a different path
	$file = str_replace('/engine/plugin/', '/mod/', $file);
	if(file_exists($file)){
		require_once $file;
		return true;
	}

}