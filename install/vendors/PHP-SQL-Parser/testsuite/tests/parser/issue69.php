<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$sql = "select * from table1 where col1<>col2 or col3 is null";
$parser = new PHPSQLParser($sql, true);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue69.serialized');
eq_array($p, $expected, 'col is null should not fail.');
