<?php
require_once(dirname(__FILE__) . "/../../../php-sql-parser.php");
require_once(dirname(__FILE__) . "/../../test-more.php");

$parser = new PHPSQLParser();

$sql = "SELECT SQL_CALC_FOUND_ROWS SmTable.*, MATCH (SmTable.fulltextsearch_keyword) AGAINST ('google googles') AS keyword_score FROM SmTable WHERE SmTable.status = 'A' AND (SmTable.country_id = 1 AND SmTable.state_id = 10) AND MATCH (SmTable.fulltextsearch_keyword) AGAINST ('google googles') ORDER BY SmTable.level DESC, keyword_score DESC LIMIT 0,10";
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'issue12.serialized');
eq_array($p, $expected, 'issue 12');
