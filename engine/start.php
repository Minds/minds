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

/*
 * No settings means a fresh install
 */
if (!file_exists(dirname(__FILE__) . '/settings.php') && !defined('__MINDS_INSTALLING__')) {
	header("Location: install.php");
	exit;
}

/**
 * The time with microseconds when the Elgg engine was started.
 *
 * @global float
 */
global $START_MICROTIME;
$START_MICROTIME = microtime(true);


define('__MINDS_ROOT__', dirname(dirname(__FILE__)));

/**
 * Autoloader
 */
require_once(dirname(__FILE__) . '/autoload.php');

$minds = new minds\core\minds();
$minds->start();
