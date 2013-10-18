<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();
$sql = "SELECT	sp.level,
		CASE sp.level
			WHEN 'bronze' THEN 0
			WHEN 'silver' THEN 1
			WHEN 'gold' THEN 2
			ELSE -1
		END AS levelnum,
		sp.alt_en,
		sp.alt_pl,
		DATE_FORMAT(sp.vu_start,'%Y-%m-%d %T') AS vu_start,
		DATE_FORMAT(sp.vu_stop,'%Y-%m-%d %T') AS vu_stop,
		ABS(TO_DAYS(now()) - TO_DAYS(sp.vu_start)) AS frdays,
		ABS(TO_DAYS(now()) - TO_DAYS(sp.vu_stop)) AS todays,
		IF(ISNULL(TO_DAYS(sp.vu_start)) OR ISNULL(TO_DAYS(sp.vu_stop))
			, 1
			, IF(TO_DAYS(now()) < TO_DAYS(sp.vu_start)
				, TO_DAYS(now()) - TO_DAYS(sp.vu_start)
				, IF(TO_DAYS(now()) > TO_DAYS(sp.vu_stop)
					, TO_DAYS(now()) - TO_DAYS(sp.vu_stop)
					, 0))) AS status,
		st.id,
		SUM(IF(st.type='view',1,0)) AS view,
		SUM(IF(st.type='click',1,0)) AS click
FROM	stats AS st,
		sponsor AS sp
WHERE	st.id=sp.id
GROUP BY st.id
ORDER BY sp.alt_en asc, sp.alt_pl asc";
$parser->parse($sql);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue31.serialized');
eq_array($p, $expected, 'very complex statement with keyword view as alias');
