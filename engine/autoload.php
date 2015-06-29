<?php
if(!defined('__MINDS_ROOT__'))
        define('__MINDS_ROOT__', dirname(dirname(__FILE__)));
// prep core classes to be autoloadable
spl_autoload_register('_minds_autoload');
//elgg_register_classes(dirname(__FILE__) . '/legacy/classes');

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

	if(file_exists(__MINDS_ROOT__."/engine/classes/$class.php")){
		include(__MINDS_ROOT__."/engine/classes/$class.php"); 
		return true;
	}
	
	if(isset($CONFIG->classes[$class])){
		include($CONFIG->classes[$class]);
		return true;
	}
			
	$file = dirname(dirname(__FILE__)) . '/'. preg_replace('/minds/i', 'engine', str_replace('\\', '/', $class),1) . '.php'; 
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
	
	//pages are a bit funny and I think they should be moved
	$file = str_replace('/engine/pages/', '/pages/', $file);
	if(file_exists($file)){
		require_once $file;
		return true;
	}

    //test have a different path also
    $file = str_replace('/engine/tests/', '/tests/', $file);
    if(file_exists($file)){
        require_once $file;
        return true;
    }

}
