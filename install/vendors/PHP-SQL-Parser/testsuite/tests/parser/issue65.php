<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$sql = "select i1, count(*) cnt from test.s1 group by i1";
$parser = new PHPSQLParser($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue65.serialized');
eq_array($p, $expected, 'It treats the alias as a colref.');
