<?php

require('engine/start.php');

$mark = get_user_by_username('mark');
var_dump($mark);
$mark->makeAdmin();
$mark->purgeCache();
