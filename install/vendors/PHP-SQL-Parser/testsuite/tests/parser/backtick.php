<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();
$sql = 'SELECT c1.`some_column` or `c1`.`another_column` or c1.`some column` as `an alias`
          from some_table an_alias group by `an alias`, `alias2`;';
$parser->parse($sql);
$p = $parser->parsed;
ok($parser->parsed['SELECT'][0]['alias']['name'] == 'an alias');
ok($parser->parsed['SELECT'][0]['sub_tree'][4]['base_expr'] == 'c1.`some column`');
ok($parser->parsed['GROUP'][0]['expr_type'] == 'alias');


$sql = "INSERT INTO test (`name`) VALUES ('ben\\'s test containing an escaped quote')";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'backtick1.serialized');
eq_array($p, $expected, "issue 35: ben's test");
