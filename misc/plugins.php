<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$plugins = array('guard');
foreach($plugins as $plugin_id){
	$plugin = new ElggPlugin($plugin_id);
	$plugin->activate();
}
