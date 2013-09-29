<?php

require_once(dirname(__FILE__) . "/../../../php-sql-parser.php");
require_once(dirname(__FILE__) . "/../../test-more.php");

$parser = new PHPSQLParser();

$sql = "SELECT q.qid, question, gid FROM questions as q WHERE (select count(*) from answers as a where a.qid=q.qid and scale_id=0)=0 and sid=11929 AND type IN ('F', 'H', 'W', 'Z', '1') and q.parent_qid=0";
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'inlist1.serialized');
eq_array($p, $expected, 'in list within WHERE clause');

