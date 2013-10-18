<?php

require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../../php-sql-creator.php');
require_once(dirname(__FILE__) . '/../../test-more.php');


$sql = "UPDATE `table` SET a = 15, b = 'haha' WHERE x = now()";
$parser = new PHPSQLParser($sql);
$creator = new PHPSQLCreator($parser->parsed);
$created = $creator->created;
$expected = getExpectedValue(dirname(__FILE__), 'update.sql', false);
ok($created === $expected, 'UPDATE with function');