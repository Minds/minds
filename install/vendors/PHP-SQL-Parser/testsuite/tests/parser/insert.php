<?php
require_once(dirname(__FILE__) . "/../../../php-sql-parser.php");
require_once(dirname(__FILE__) . "/../../test-more.php");

$parser = new PHPSQLParser();

$sql = "insert into SETTINGS_GLOBAL (stg_value,stg_name) values('','force_ssl')";
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'insert1.serialized');
eq_array($p, $expected, 'insert some data into table');

$sql = " INSERT INTO settings_global VALUES ('DBVersion', '146')";
$p = $parser->parse($sql, true);
$expected = getExpectedValue(dirname(__FILE__), 'insert2.serialized');
eq_array($p, $expected, 'insert some data into table with positions');

$sql = "INSERT INTO surveys ( SID, OWNER_ID, ADMIN, ACTIVE, EXPIRES, STARTDATE, ADMINEMAIL, ANONYMIZED, FAXTO, FORMAT, SAVETIMINGS, TEMPLATE, LANGUAGE, DATESTAMP, USECOOKIE, ALLOWREGISTER, ALLOWSAVE, AUTOREDIRECT, ALLOWPREV, PRINTANSWERS, IPADDR, REFURL, DATECREATED, PUBLICSTATISTICS, PUBLICGRAPHS, LISTPUBLIC, HTMLEMAIL, TOKENANSWERSPERSISTENCE, ASSESSMENTS, USECAPTCHA, BOUNCE_EMAIL, EMAILRESPONSETO, EMAILNOTIFICATIONTO, TOKENLENGTH, SHOWXQUESTIONS, SHOWGROUPINFO, SHOWNOANSWER, SHOWQNUMCODE, SHOWWELCOME, SHOWPROGRESS, ALLOWJUMPS, NAVIGATIONDELAY, NOKEYBOARD, ALLOWEDITAFTERCOMPLETION ) 
VALUES ( 32225, 1, 'André', 'N', null, null, 'hello@zks.uni-leipzig.de', 'N', '', 'G', 'N', 'default', 'de-informal', 'N', 'N', 'N', 'Y', 'N', 'N', 'N', 'N', 'N', a_function('2012-02-16','YYYY-MM-DD'), 'N', 'N', 'Y', 'Y', 'N', 'N', 'D', 'hello@zks.uni-leipzig.de', '', '', 15, 'Y', 'B', 'Y', 'X', 'Y', 'Y', 'N', 0, 'N', 'N' )";
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'insert3.serialized');
eq_array($p, $expected, 'insert with user-function');
