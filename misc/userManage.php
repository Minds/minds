<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access(true);

$db = new minds\core\data\call('user_index_to_guid');
$db->removeRow('bill@minds.com');
$db->removeRow('billottman'); 
exit;


//reset_login_failure_count($john->guid);
$user = new ElggUser('culture');

$user->icontime = time();
//var_dump(force_user_password_reset($user->guid, 'temp123'));
//var_dump($user);
//$user->makeAdmin();
//$user->enable();
$user->save();


