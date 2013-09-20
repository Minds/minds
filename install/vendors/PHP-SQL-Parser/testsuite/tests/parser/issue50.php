<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

// TODO: not solved, charsets are not possible at the moment

$parser = new PHPSQLParser();

$sql = "SELECT _utf8'hi'";
$parser->parse($sql, false);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue50.serialized');
eq_array($p, $expected, 'does not die if query contains _utf8');


$sql = "SELECT _utf8'hi' COLLATE latin1_german1_ci";
$parser->parse($sql, false);
$p = $parser->parsed;

# hex value

$sql = "SELECT _utf8 x'AABBCC'";

$sql = "SELECT _utf8 0xAABBCC";

# binary value
$sql = "SELECT _utf8 b'0001'";

$sql = "SELECT _utf8 0b0001";


