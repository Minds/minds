<?php

require_once(dirname(dirname(__FILE__)) . '/engine/start.php');
elgg_set_ignore_access(true);
$user = new ElggUser('mark');
var_dump($user->icontime);
$size= 'master';

var_dump($user->getIconURL());
