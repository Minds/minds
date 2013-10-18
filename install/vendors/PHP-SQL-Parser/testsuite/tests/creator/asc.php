<?php

require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../../php-sql-creator.php');
require_once(dirname(__FILE__) . '/../../test-more.php');



$sql = "SELECT qid FROM QUESTIONS WHERE gid='1' and language='de-informal' ORDER BY question_order, title ASC";
$parser = new PHPSQLParser($sql);
$creator = new PHPSQLCreator($parser->parsed);
$created = $creator->created;
$expected = getExpectedValue(dirname(__FILE__), 'asc.sql', false);
ok($created === $expected, 'explicit ASC statement');
