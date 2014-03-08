<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access();

$john = get_user_by_username('john');

var_dump($john);
//reset_login_failure_count($john->guid);
//var_dump(force_user_password_reset($john->guid, 'temp123'));

//login($john);


exit;
$user = get_entity(281927423002415104);

var_dump($user);
$user->save();
exit;
foreach($user as $k => $v){
	if(strpos($k, 'login_failure') !== false){
		unset($k);
	}
}
$user->time_created = 1348444800;
$user->save();
exit;
var_dump($user->isEnabled());
$user->username = 'john';
var_dump($user->save());
//$mark->makeAdmin();
//$user->purgeCache();
