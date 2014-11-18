<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access(true);

$user = new minds\entities\user('markandrewculp');
$cluster = new minds\core\clusters();
$cluster->syncCarousels($user);
var_dump($user); exit;
//var_dump($user); exit;
//$user->delete(); exit;
//$user = new minds\entities\user('hobbesdeutschjr.');
//$user->cache = true;
//$user->username = 'HobbesDeutschJr';
//$user->save();

$db = new minds\core\data\call('user_index_to_guid');
$data = $db->getRow('music');

foreach($data as $guid => $time){
	if($guid != '100000000000002125'){
		$db->removeAttributes('music', array($guid));
	}
}
exit;
//login(new minds\entities\user('mark'));
*/
$db = new minds\core\data\call();
$db->getCF('session')->truncate();
//reset_login_failure_count($john->guid);
$user = new ElggUser('mark');
$user->makeAdmin();

