<?php

require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../../php-sql-creator.php');
require_once(dirname(__FILE__) . '/../../test-more.php');


$sql = " SELECT a.*, surveyls_title, surveyls_description, surveyls_welcometext, surveyls_url  FROM SURVEYS AS a INNER JOIN SURVEYS_LANGUAGESETTINGS on (surveyls_survey_id=a.sid and surveyls_language=a.language)  order by active DESC, surveyls_title";
$parser = new PHPSQLParser($sql);
$creator = new PHPSQLCreator($parser->parsed);
$created = $creator->created;
$expected = getExpectedValue(dirname(__FILE__), 'join.sql', false);
ok($created === $expected, 'a join');
