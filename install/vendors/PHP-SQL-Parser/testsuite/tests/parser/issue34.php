<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();
$sql = "SELECT * FROM cache as t";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue34a.serialized');
eq_array($p, $expected, 'SELECT statement with keyword CACHE as tablename');

$sql = "INSERT INTO CACHE VALUES (1);";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue34b.serialized');
eq_array($p, $expected, 'INSERT statement with keyword CACHE as tablename');
