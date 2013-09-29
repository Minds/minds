<?php
require_once(dirname(__FILE__) . "/../../../php-sql-parser.php");
require_once(dirname(__FILE__) . "/../../test-more.php");

$test    = str_repeat('0', 18000);
$query  = "UPDATE club SET logo='$test' WHERE id=1";
 
$parser = new PHPSQLParser();
$p = $parser->parse($query);
$expected = getExpectedValue(dirname(__FILE__), 'issue11.serialized');
eq_array($p, $expected, 'very long statement');
