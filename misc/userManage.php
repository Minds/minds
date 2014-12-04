<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access(true);
exit;
$user = new minds\entities\user('ottman');
login($user); 

exit;
//$cluster = new minds\core\clusters();
//$cluster->syncCarousels($user);
//var_dump($user); exit;
//var_dump($user); exit;
//$user->delete(); exit;
//$user = new minds\entities\user('hobbesdeutschjr.');
//$user->cache = true;
//$user->username = 'HobbesDeutschJr';
//$user->save();

$db = new minds\core\data\call('user_index_to_guid');
$data = $db->getRow('education');


foreach($data as $guid => $time){
	if($guid != "100000000000091117"){
		$db->removeAttributes('education', array($guid));
	}
}
exit;
//login(new minds\entities\user('mark'));
$db = new minds\core\data\call();
$db->getCF('session')->truncate();
//reset_login_failure_count($john->guid);
$user = new ElggUser('mark');
$user->makeAdmin();

