<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');
exit;
elgg_set_ignore_access(true);

//reset_login_failure_count($john->guid);
//var_dump(force_user_password_reset($john->guid, 'temp123'));
var_dump(elgg_get_entities(array('type'=>'user')));
//login($john);
$user = new ElggUser('admin');
//var_dump(force_user_password_reset($user, 'temp123'));
//var_dump($user);
$user->makeAdmin();



