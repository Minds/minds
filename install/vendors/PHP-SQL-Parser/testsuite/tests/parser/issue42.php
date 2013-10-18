<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();

$sql = "SELECT 'a string with an escaped quote \' in it' AS some_alias FROM some_table";
$parser->parse($sql, true);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue42.serialized');
eq_array($p, $expected, 'escaped quote in string constant');