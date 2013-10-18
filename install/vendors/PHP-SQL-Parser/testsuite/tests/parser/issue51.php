<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();

// TODO: there is an error in output, the comma is recognized as colref
$sql = "SELECT CAST( 12 AS decimal( 9, 3 ) )";
$parser->parse($sql, true);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue51.serialized');
eq_array($p, $expected, 'does not die if query contains cast expression');