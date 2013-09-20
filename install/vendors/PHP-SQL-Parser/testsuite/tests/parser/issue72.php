<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

# user defined variable
$sql = "update table set column=@max";
$parser = new PHPSQLParser($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue72.serialized');
eq_array($p, $expected, 'user defined variables should not fail');
