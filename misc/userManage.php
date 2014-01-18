<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access();

//$blog = get_entity(268112608572215296);
//var_dump($blog->getOwnerEntity());
//$user = get_user_by_username('john');
//$user->username = 'john';
//$user->save();
//var_dump($user->getUrl());
global $DB;

$user = get_entity(100000000000000134,'user');
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
