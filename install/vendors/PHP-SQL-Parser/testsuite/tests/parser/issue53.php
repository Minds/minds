<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();

$sql = "SELECT * FROM table WHERE a=1 ORDER BY c DESC LIMIT 10 OFFSET 20";
$parser->parse($sql, false);
$p = $parser->parsed;

$expected = getExpectedValue(dirname(__FILE__), 'issue53a.serialized');
eq_array($p, $expected, 'limit with offset');


$sql = "SELECT * FROM table WHERE a=1 ORDER BY c DESC LIMIT 20, 10";
$parser->parse($sql, false);
$p = $parser->parsed;

$expected = getExpectedValue(dirname(__FILE__), 'issue53a.serialized');
eq_array($p, $expected, 'limit with comma-separated offset');


$sql = "SELECT * FROM table WHERE a=1 ORDER BY c DESC LIMIT 10";
$parser->parse($sql, false);
$p = $parser->parsed;

$expected = getExpectedValue(dirname(__FILE__), 'issue53b.serialized');
eq_array($p, $expected, 'limit without offset');