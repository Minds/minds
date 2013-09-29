<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();
$sql = 'SELECT c1
          from some_table an_alias
	where d > 0;';
$parser->parse($sql);
$p = $parser->parsed;
ok($parser->parsed['WHERE'][2]['base_expr'] == '0');