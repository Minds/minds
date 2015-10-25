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

define('__MINDS_ROOT__', dirname(__FILE__));

/**
 * Autoloader
 */
require_once(__MINDS_ROOT__ . '/vendor/autoload.php');

$minds = new Minds\Core\Minds();
$minds->start();
