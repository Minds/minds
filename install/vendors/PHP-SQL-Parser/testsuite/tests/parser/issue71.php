<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$sql = "select * from table1 as event";
$parser = new PHPSQLParser($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue71a.serialized');
eq_array($p, $expected, 'infinite loop on table alias "event"');

$sql = "select acol from table as data";
$parser = new PHPSQLParser($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue71b.serialized');
eq_array($p, $expected, 'infinite loop on table alias "data"');

