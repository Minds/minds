<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();

$sql = 'SELECT c1
          from some_table an_alias
	where d>=0 and d>0 and d>1 and d>-1 and d<2 and d<>0  or d <> 0 or d<>"test1" or d <> "test2";';
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'gtltop.serialized');
eq_array($p, $expected, 'a lot of where clauses');
