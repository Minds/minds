<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

$mc = new MindsComments();
$create = $mc->create('entity', '301885764336095232', 'hello!');
var_dump($create);
