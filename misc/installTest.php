<?php

require_once('/var/www/multisite/vendor/autoload.php');

$db = new minds\core\data\call(NULL, 'testoct1', array('10.0.9.10'));
$attrs = array(	  "strategy_options" => array("replication_factor" => "2"));	
echo "keyspace \n";
$db->createKeyspace($attrs);

echo "schema \n";
$db->installSchema();
$db->installSchema();
echo "done \n";
$db->dropKeyspace(true);
