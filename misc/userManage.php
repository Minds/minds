<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access();

//reset_login_failure_count($john->guid);
//var_dump(force_user_password_reset($john->guid, 'temp123'));

//login($john);
$user = get_user_by_username('akshaykumar14');
var_dump($user);
$user->validated = 'yes';
$user->save();
force_user_password_reset($user->guid, 'temp123');



