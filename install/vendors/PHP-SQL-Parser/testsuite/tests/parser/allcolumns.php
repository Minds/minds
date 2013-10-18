<?php

require_once(dirname(__FILE__) . "/../../../php-sql-parser.php");
require_once(dirname(__FILE__) . "/../../test-more.php");

$parser = new PHPSQLParser();

$sql="SELECT * FROM FAILED_LOGIN_ATTEMPTS WHERE ip='192.168.50.5'";
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'allcolumns1.serialized');
eq_array($p, $expected, 'single all column alias');


$sql="SELECT a * b FROM tests";
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'allcolumns2.serialized');
eq_array($p, $expected, 'multiply two columns');


$sql="SELECT count(*) FROM tests";
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'allcolumns3.serialized');
eq_array($p, $expected, 'special function count(*)');


$sql="SELECT a.* FROM FAILED_LOGIN_ATTEMPTS a";
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'allcolumns4.serialized');
eq_array($p, $expected, 'single all column alias with table alias');


$sql="SELECT a, * FROM tests";
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'allcolumns5.serialized');
eq_array($p, $expected, 'column reference and a single all column alias');
