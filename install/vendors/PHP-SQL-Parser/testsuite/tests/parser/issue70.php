<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$sql = "select column from table where col=\"value\"";
$parser = new PHPSQLParser($sql, true);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue70.serialized');
eq_array($p, $expected, 'quotes after an operator should not fail.');
