<?php
require_once(dirname(dirname(__FILE__)).'/engine/start.php');

elgg_set_ignore_access(true);

$old = 'mac35.minds.com';
$new = 'www.mac35.com';

$db = new minds\core\data\call('domain', 'elggmultisite');

$data = $db->getRow($old);
$db->insert($new, $data);


exit;
$node = new MindsNode(345628224509710336);
$node->tier_guid = '347826227953799168';
$node->save();
