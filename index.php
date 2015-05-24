<?php
/**
 * Elgg index page for web-based applications
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(__FILE__) . "/engine/start.php");

error_reporting(E_ALL); 
ini_set( 'display_errors','1');

$router = new Minds\Core\router();
$router->route();