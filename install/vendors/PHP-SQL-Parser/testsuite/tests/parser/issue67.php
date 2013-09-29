<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

# the SET statement doesn't work completely, SESSION is not a colref!
$sql = "SET SESSION group_concat_max_len = @@max_allowed_packet";
$parser = new PHPSQLParser($sql, true);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue67a.serialized');
eq_array($p, $expected, '@ character after operator should not fail.');

# this is ok
$sql = "SET @a = 1";
$parser = new PHPSQLParser($sql, true);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue67b.serialized');
eq_array($p, $expected, 'user defined variables should not fail');
