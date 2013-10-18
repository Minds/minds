<?php

require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();

$sql = 'SELECT *
    FROM (t1 LEFT JOIN t2 ON t1.a=t2.a)
         LEFT JOIN t3
         ON t2.b=t3.b OR t2.b IS NULL';
$parser->parse($sql, true);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'nested1.serialized');
eq_array($p, $expected, 'nested left joins');

$sql = "SELECT * FROM t1 LEFT JOIN (t2, t3, t4)
                 ON (t2.a=t1.a AND t3.b=t1.b AND t4.c=t1.c)";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'nested2.serialized');
eq_array($p, $expected, 'left joins with multiple tables');
