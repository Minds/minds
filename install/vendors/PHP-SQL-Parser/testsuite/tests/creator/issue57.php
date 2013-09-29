<?php

require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../../php-sql-creator.php');
require_once(dirname(__FILE__) . '/../../test-more.php');


$sql = "SELECT a.*, SUM(b.home) AS home,b.language,l.image,l.sef,l.title_native
FROM iuz6l_menu_types AS a
LEFT JOIN iuz6l_menu AS b ON b.menutype = a.menutype AND b.home != 0
LEFT JOIN iuz6l_languages AS l ON l.lang_code = language
WHERE (b.client_id = 0 OR b.client_id IS NULL)
GROUP BY a.id, a.menutype, a.description, a.title, b.menutype,b.language,l.image,l.sef,l.title_native";
$parser = new PHPSQLParser($sql);
$creator = new PHPSQLCreator($parser->parsed);
$created = $creator->created;
$expected = getExpectedValue(dirname(__FILE__), 'issue57.sql', false);
ok($created === $expected, 'constants in ref-clause');