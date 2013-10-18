<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$sql = "SELECT lcase(dummy.b) FROM dummy ORDER BY dummy.a, LCASE(dummy.b)";
$parser = new PHPSQLParser($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue61.serialized');
eq_array($p, $expected, 'functions/expressions within ORDER-BY');
