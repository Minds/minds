<?php

require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../../php-sql-creator.php');
require_once(dirname(__FILE__) . '/../../test-more.php');



$sql = 'SELECT *
    FROM (t1 LEFT JOIN t2 ON t1.a=t2.a)
         LEFT JOIN t3
         ON t2.b=t3.b OR t2.b IS NULL';
$parser = new PHPSQLParser($sql);
$creator = new PHPSQLCreator($parser->parsed);
$created = $creator->created;
$expected = getExpectedValue(dirname(__FILE__), 'left.sql', false);
ok($created === $expected, 'left joins and table-expression');
