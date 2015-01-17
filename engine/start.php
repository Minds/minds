<?php
/**
 * Bootstraps the Elgg engine.
 *
 * This file loads the full Elgg engine, checks the installation
 * state, and triggers a series of events to finish booting Elgg:
 * 	- {@elgg_event boot system}
 * 	- {@elgg_event init system}
 * 	- {@elgg_event ready system}
 *
 * If Elgg is fully uninstalled, the browser will be redirected to an
 * installation page.
 *
 * @see install.php
 * @package Elgg.Core
 * @subpackage Core
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
//require_once(dirname(__FILE__) . '/autoload.php');

$minds = new Minds\Core\minds();
$minds->start();
