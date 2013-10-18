<?php
require_once(dirname(__FILE__) . "/../../../php-sql-parser.php");
require_once(dirname(__FILE__) . "/../../test-more.php");

$parser = new PHPSQLParser();

$sql = 'SELECT colA hello From test t';
$p = $parser->parse($sql, true);
ok($p['SELECT'][0]['position'] == 7, 'position of column');
ok($p['SELECT'][0]['alias']['position'] == 12, 'position of column alias');
ok($p['FROM'][0]['position'] == 23, 'position of table');
ok($p['FROM'][0]['alias']['position'] == 28, 'position of table alias');

$sql = "SELECT colA hello From test\nt";
$p = $parser->parse($sql, true);
ok($p['SELECT'][0]['position'] == 7, 'position of column');
ok($p['SELECT'][0]['alias']['position'] == 12, 'position of column alias');
ok($p['FROM'][0]['position'] == 23, 'position of table');
ok($p['FROM'][0]['alias']['position'] == 28, 'position of table alias');

$sql = "SELECT a.*, c.*, u.users_name FROM SURVEYS as a  INNER JOIN SURVEYS_LANGUAGESETTINGS as c ON ( surveyls_survey_id = a.sid AND surveyls_language = a.language ) AND surveyls_survey_id=a.sid and surveyls_language=a.language  INNER JOIN USERS as u ON (u.uid=a.owner_id)  ORDER BY surveyls_title";
$p = $parser->parse($sql, true);
$expected = getExpectedValue(dirname(__FILE__), 'positions1.serialized');
eq_array($p, $expected, 'a long query with join and order clauses');
