<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();

$sql = "INSERT INTO test (`name`, `test`) VALUES ('\'Superman\'', ''), ('\'Superman\'', '')";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue36a.serialized');
eq_array($p, $expected, 'INSERT statement with escaped quotes and multiple records');


$sql = "INSERT INTO test (`name`, `test`) VALUES ('\'Superman\'', '')";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue36b.serialized');
eq_array($p, $expected, 'INSERT statement with escaped quotes and one record');


$sql = "INSERT INTO test (`name`, `test`) VALUES ('\'Superman\'', ''), ('\'sdfsd\'', '')";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue36c.serialized');
eq_array($p, $expected, 'INSERT statement with escaped quotes and multiple records (2)');
