<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$parser = new PHPSQLParser();

$sql = "SELECT m.id, m.title, m.module, m.position, m.content, m.showtitle, m.params, mm.menuid
FROM kj9un_modules AS m
LEFT JOIN kj9un_modules_menu AS mm ON mm.moduleid = m.id
LEFT JOIN kj9un_extensions AS e ON e.element = m.module AND e.client_id = m.client_id
WHERE m.published = 1 AND e.enabled = 1 AND (m.publish_up = '0000-00-00 00:00:00' OR m.publish_up <= '2012-04-21 09:44:01') AND (m.publish_down = '0000-00-00 00:00:00' OR m.publish_down >= '2012-04-21 09:44:01') AND m.access IN (1,1) AND m.client_id = 0 AND (mm.menuid = 170 OR mm.menuid <= 0) AND m.language IN ('en-GB','*')
ORDER BY m.position, m.ordering";
$parser->parse($sql, true);
$p = $parser->parsed;
$expected = getExpectedValue(dirname(__FILE__), 'issue44.serialized');
eq_array($p, $expected, 'issue 44 position problem');

