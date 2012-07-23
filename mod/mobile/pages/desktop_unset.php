<?php 
define('externalpage',true);
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
global $CONFIG;

	unset($_SESSION['view_desktop']);
	
	forward('/activity');

?>
