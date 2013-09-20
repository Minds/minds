<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();

$sql = "SELECT title, introtext
FROM kj9un_content
WHERE `id`='159'";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue43.serialized');
eq_array($p, $expected, 'problem with linefeed after tablename');
