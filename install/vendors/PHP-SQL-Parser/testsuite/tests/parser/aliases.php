<?php
require_once(dirname(__FILE__) . "/../../../php-sql-parser.php");
require_once(dirname(__FILE__) . "/../../test-more.php");

$parser = new PHPSQLParser();

$sql = 'SELECT colA * colB From test t';
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'alias1.serialized');
eq_array($p, $expected, 'multiply columns with table alias');

$sql = 'select colA colA from test';
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'alias2.serialized');
eq_array($p, $expected, 'alias named like the column');

$sql = 'SELECT (select colA AS a from test t) colA From example as b';
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'alias3.serialized');
eq_array($p, $expected, 'sub-query within selection with alias');

$sql = 'SELECT (select colA AS a from testA) + (select colB b from testB) From tableC x';
$p = $parser->parse($sql, true);
$expected = getExpectedValue(dirname(__FILE__), 'alias4.serialized');
eq_array($p, $expected, 'add two sub-query results');
