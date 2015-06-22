<?php
/**
 * GUID Generator
 */

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

global $CONFIG;

$machine = isset($CONFIG->machine_id) ? ($CONFIG->machine_id) : 1;
$port = 5599;
$zks = 'localhost:2181';

$timer = new \Davegardnerisme\CruftFlake\Timer;
if ($machine !== NULL) {
        $config = new \Davegardnerisme\CruftFlake\FixedConfig($machine);
} else {
        $config = new \Davegardnerisme\CruftFlake\ZkConfig($zks);
}
$generator = new \Davegardnerisme\CruftFlake\Generator($config, $timer);
$zmqRunner = new \Davegardnerisme\CruftFlake\ZeroMq($generator, $port);
$zmqRunner->run();
