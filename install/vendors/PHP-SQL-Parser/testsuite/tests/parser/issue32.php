<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();
$sql = "UPDATE user SET lastlogin = 7, x = 3";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue32.serialized');
eq_array($p, $expected, 'update with keyword user as table');
