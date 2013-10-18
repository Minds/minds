<?php

require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../../php-sql-creator.php');
require_once(dirname(__FILE__) . '/../../test-more.php');


$sql = "SELECT col FROM table1 GROUP BY col";
$parser = new PHPSQLParser($sql);
$creator = new PHPSQLCreator($parser->parsed);
$created = $creator->created;
$expected = getExpectedValue(dirname(__FILE__), 'issue63a.sql', false);
ok($created === $expected, 'group by with colref fails.');


$sql = "SELECT col AS somealias FROM table ORDER BY somealias LIMIT 1";
$parser = new PHPSQLParser($sql);
$creator = new PHPSQLCreator($parser->parsed);
$created = $creator->created;
$expected = getExpectedValue(dirname(__FILE__), 'issue63b.sql', false);
ok($created === $expected, 'ORDER BY alias fails.');


$sql = "SELECT * FROM table LIMIT 1";
$parser = new PHPSQLParser($sql);
$creator = new PHPSQLCreator($parser->parsed);
$created = $creator->created;
$expected = getExpectedValue(dirname(__FILE__), 'issue63c.sql', false);
ok($created === $expected, 'LIMIT is ignored in output.');