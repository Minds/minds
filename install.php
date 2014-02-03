<?php
/**
 * Elgg install script
 *
 * @package Elgg
 * @subpackage Core
 */

// check for PHP 4 before we do anything else
if (version_compare(PHP_VERSION, '5.0.0', '<')) {
	echo "Your server's version of PHP (" . PHP_VERSION . ") is too old to run Elgg.\n";
	exit;
}


// Quick and dirty test to see if this is a standalone install or living inside a multisite wrapper
if (!file_exists(dirname(dirname(__FILE__)) . "/minds/start.php")) 
{
    require_once(dirname(__FILE__) . "/install/ElggInstaller.php");
    
    $installer = new ElggInstaller();
}
else
{
    require_once(dirname(dirname(__FILE__)) . "/minds/start.php");
    require_once(dirname(__FILE__) . "/install/ElggInstaller.php");
    require_once(dirname(__FILE__) . "/install/MindsMultiInstaller.php");
    
	try{
    $installer = new MindsMultiInstaller();

    $installer->setupMulti();
	} catch(Exception $e){
		var_dump($e);
	}
}

$step = get_input('step', 'welcome'); 
$installer->run($step);
