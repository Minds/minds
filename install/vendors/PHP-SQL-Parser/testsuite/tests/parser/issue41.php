<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();

$sql = "SELECT * FROM v\$mytable";
$parser->parse($sql, true);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue41.serialized');
eq_array($p, $expected, 'escaped $ in tablename');