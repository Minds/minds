<?php

require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();

$sql = 'SELECT c1
          from some_table an_alias
	where d > 5;';
$parser->parse($sql);
$p = $parser->parsed;

ok(count($p) == 3 && count($p['FROM']) == 1);
ok($p['FROM'][0]['alias']['name']=='an_alias');



$sql = 'select DISTINCT 1+2   c1, 1+ 2 as 
`c2`, sum(c2),sum(c3) as sum_c3,"Status" = CASE
        WHEN quantity > 0 THEN \'in stock\'
        ELSE \'out of stock\'
        END case_statement
, t4.c1, (select c1+c2 from t1 inner_t1 limit 1) as subquery into @a1, @a2, @a3 from t1 the_t1 left outer join t2 using(c1,c2) join t3 as tX ON tX.c1 = the_t1.c1 join t4 t4_x using(x) where c1 = 1 and c2 in (1,2,3, "apple") and exists ( select 1 from some_other_table another_table where x > 1) and ("zebra" = "orange" or 1 = 1) group by 1, 2 having sum(c2) > 1 ORDER BY 2, c1 DESC LIMIT 0, 10 into outfile "/xyz" FOR UPDATE LOCK IN SHARE MODE';

$parser = new PHPSQLParser($sql);
$p=$parser->parsed;

ok(count($p['SELECT']) == 7, 'seven selects');
ok($p['SELECT'][0]['alias']['name'] == 'c1');
ok($p['SELECT'][1]['alias']['name'] == 'c2');
ok($p['SELECT'][2]['alias']['name'] == '', 'no alias on sum(c2)');
ok($p['SELECT'][3]['alias']['name'] == 'sum_c3');
ok($p['SELECT'][4]['alias']['name'] == 'case_statement', 'case statement');
ok($p['SELECT'][5]['alias']['name'] == '', 'no alias on t4.c1');
ok($p['SELECT'][6]['alias']['name'] == 'subquery');
