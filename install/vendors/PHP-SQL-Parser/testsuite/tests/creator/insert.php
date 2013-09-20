<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../../php-sql-creator.php');
require_once(dirname(__FILE__) . '/../../test-more.php');



$sql = "INSERT INTO test (`name`, `test`) VALUES ('\'Superman\'', ''), ('\'Superman\'', '')";
$parser = new PHPSQLParser($sql);
$creator = new PHPSQLCreator($parser->parsed);
$created = $creator->created;
$expected = getExpectedValue(dirname(__FILE__), 'insert1.sql', false);
ok($created === $expected, 'multiple records within INSERT');


$sql = "INSERT INTO test (`name`, `test`) VALUES ('\'Superman\'', '')";
$parser = new PHPSQLParser($sql);
$creator = new PHPSQLCreator($parser->parsed);
$created = $creator->created;
$expected = getExpectedValue(dirname(__FILE__), 'insert2.sql', false);
ok($created === $expected, 'a simple INSERT statement');


$sql = "INSERT INTO test (`name`, `test`) VALUES ('\'Superman\'', ''), ('\'sdfsd\'', '')";
$parser = new PHPSQLParser($sql);
$creator = new PHPSQLCreator($parser->parsed);
$created = $creator->created;
$expected = getExpectedValue(dirname(__FILE__), 'insert3.sql', false);
ok($created === $expected, 'multiple records within INSERT (2)');
