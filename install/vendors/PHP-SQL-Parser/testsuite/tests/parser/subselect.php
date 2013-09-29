<?php
require_once(dirname(__FILE__) . "/../../../php-sql-parser.php");
require_once(dirname(__FILE__) . "/../../test-more.php");

$parser = new PHPSQLParser();

$sql = 'SELECT (select colA FRom TableA) as b From test t';
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'subselect1.serialized');
eq_array($p, $expected, 'sub-select with alias');

$sql = 'SELECT a.uid, a.users_name FROM USERS AS a LEFT JOIN (SELECT uid AS id FROM USER_IN_GROUPS WHERE ugid = 1) AS b ON a.uid = b.id WHERE id IS NULL ORDER BY a.users_name';
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'subselect2.serialized');
eq_array($p, $expected, 'sub-select as table replacement with alias');
