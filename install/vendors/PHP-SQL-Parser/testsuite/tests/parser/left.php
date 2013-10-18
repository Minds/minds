<?php

require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();

$sql = 'SELECT a.field1, b.field1, c.field1
  FROM tablea a 
  LEFT JOIN tableb b ON b.ida = a.id
  LEFT JOIN tablec c ON c.idb = b.id;';

$parser->parse($sql, true);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'left1.serialized');
eq_array($p, $expected, 'left join with alias');

$sql = 'SELECT a.field1, b.field1, c.field1
  FROM tablea a 
  LEFT OUTER JOIN tableb b ON b.ida = a.id
  RIGHT JOIN tablec c ON c.idb = b.id
  JOIN tabled d USING (d_id)
  right outer join e on e.id = a.e_id;
  left join e e2 using (e_id)
  join e e3 on (e3.e_id = e2.e_id)';

$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'left2.serialized');
eq_array($p, $expected, 'right and left outer joins');
