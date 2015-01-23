<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$db = new Minds\core\data\call(NULL, uniqid());


/**
 * Does the keyspace exist
 */
$start = microtime(true);
try{
	if($db->pool->describe_keyspace())
		$exists = 'yes';
}catch(\Exception $e){
	$exists = 'no';
}
$ms = (microtime(true) - $start) * 1000;


echo "Returned keyspace exists:" . print_r($exists, true) . " in $ms (ms)\n";

/**
 * Test creating a keyspace
 */
$start = microtime(true);
$created = $db->createKeyspace(array(	  "strategy_options" => array("replication_factor" => "2")));
$ms = (microtime(true) - $start) * 1000;


echo "Returned keyspace created:" . print_r($created, true) . " in $ms (ms)\n";
sleep(10); //let the keyspace calm down
/**
 * Test creating a cf
 */
$start = microtime(true);
$created = $db->createCF('testing');
$ms = (microtime(true) - $start) * 1000;


echo "Returned cf created:" . print_r($created, true) . " in $ms (ms)\n";


/**
 * Drop the keyspace
 */
$start = microtime(true);
$dropped = $db->dropKeyspace(true);
$ms = (microtime(true) - $start) * 1000;


echo "Returned keyspace  dropped:" . print_r($dropped, true) . " in $ms (ms)\n";
