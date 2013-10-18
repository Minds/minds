<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();
$sql = "SELECT foo.a FROM test foo WHERE RIGHT(REPLACE(foo.bar,'(0',''),7) = 'a'";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue30.serialized');
eq_array($p, $expected, 'parenthesis within string literals within function parameter list');
