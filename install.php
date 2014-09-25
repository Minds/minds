<?php
/**
 * Elgg install script
 *
 * @package Elgg
 * @subpackage Core
 */

date_default_timezone_set('UTC');
// Quick and dirty test to see if this is a standalone install or living inside a multisite wrapper
if (!file_exists(dirname(dirname(dirname(__FILE__))) . "/autoload.php")) {
    require(dirname(__FILE__).'/vendor/autoload.php');
    require_once(dirname(__FILE__) . "/install/ElggInstaller.php");
    
    $installer = new ElggInstaller();
} else {
    require_once(dirname(dirname(dirname(__FILE__))) . "/autoload.php");
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
