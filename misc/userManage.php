<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access(true);

$db = new minds\core\data\call();
$db->getCF('session')->truncate();
//reset_login_failure_count($john->guid);
$user = new ElggUser('mark');
$user->makeAdmin();

