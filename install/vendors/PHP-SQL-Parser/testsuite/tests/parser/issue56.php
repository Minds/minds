<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

# optimizer/index hints
# not solved
$parser = new PHPSQLParser();
$sql = "insert /* +APPEND */ into TableName (Col1,col2) values(1,'pol')";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue56a.serialized');
eq_array($p, $expected, 'optimizer hint within INSERT');

# inline comment
# not solved
$sql = "SELECT acol -- an inline comment
FROM --another comment
table
WHERE x = 1";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue56b.serialized');
eq_array($p, $expected, 'inline comment should not fail, issue 56');
