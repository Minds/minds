<?php
/**
 * Bootstraps Minds engine
 */

/**
 * The time with microseconds when the Elgg engine was started.
 *
 * @global float
 */
global $START_MICROTIME;
$START_MICROTIME = microtime(true);
date_default_timezone_set('UTC');

define('__MINDS_ROOT__', dirname(dirname(__FILE__)));

/**
 * Autoloader
 */
if(file_exists(dirname(dirname(__MINDS_ROOT__)) ."/autoload.php"))
	require_once(dirname(dirname(__MINDS_ROOT__)) ."/autoload.php");
else
	require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');

$minds = new Minds\Core\Minds();
$minds->start();
