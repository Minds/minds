<?php

require_once(dirname(__FILE__) . "/start.php");

error_reporting(E_ALL);
//ini_set('display_errors', '1');

$router = new Minds\Core\Router();
$router->route();
