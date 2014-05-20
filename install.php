<?php
/**
 * Elgg install script
 *
 * @package Elgg
 * @subpackage Core
 */

define('__MINDS_ROOT__', dirname(__FILE__));
require(dirname(__FILE__).'/engine/autoload.php');

// Quick and dirty test to see if this is a standalone install or living inside a multisite wrapper
if (!file_exists(dirname(dirname(__FILE__)) . "/minds/start.php")) {
    require_once(dirname(__FILE__) . "/install/ElggInstaller.php");
    
    $installer = new ElggInstaller();
} else {
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
