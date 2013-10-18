<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();

$sql = 'SELECT
1';
$p=$parser->parse($sql);

ok(count($p) == 1 && count($p['SELECT']) == 1);
ok($p['SELECT'][0]['expr_type'] == 'const');
ok($p['SELECT'][0]['base_expr'] == '1');
ok($p['SELECT'][0]['sub_tree'] == '');


$sql = 'SELECT 1+2 c1, 1+2 as c2, 1+2,  sum(a) sum_a_alias,a,a an_alias, a as another_alias,terminate
          from some_table an_alias
	where d > 5;';
$parser->parse($sql);
$p = $parser->parsed;

ok(count($p) == 3 && count($p['SELECT']) == 8);
ok($p['SELECT'][count($p['SELECT'])-1]['base_expr'] == 'terminate');
ok(count($p) == 3 && count($p['FROM']) == 1);
ok(count($p) == 3 && count($p['WHERE']) == 3);


$parser->parse('SELECT NOW( ),now(),sysdate( ),sysdate () as now');
ok($parser->parsed['SELECT'][3]['base_expr'] == 'sysdate');


$sql = " SELECT a.*, surveyls_title, surveyls_description, surveyls_welcometext, surveyls_url  FROM SURVEYS AS a INNER JOIN SURVEYS_LANGUAGESETTINGS on (surveyls_survey_id=a.sid and surveyls_language=a.language)  order by active DESC, surveyls_title";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'select1.serialized');
eq_array($p, $expected, 'a test for ref_clauses');


$sql = "SELECT pl_namespace,pl_title FROM `pagelinks` WHERE pl_from = '1' FOR UPDATE";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'select2.serialized');
eq_array($p, $expected, 'select for update');

