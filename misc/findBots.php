<?php

require(dirname(dirname(__FILE__)).'/engine/start.php');
elgg_set_ignore_access(true);
try{
//$db = new minds\core\data\call();
//$db->getCF('session')->truncate();
}catch(Exception $e){}
/*$users = elgg_get_entities(array('type'=>'user', 'limit'=>400));
foreach($users as $user){

	$result = validateUser($user->email, $user->ip);
	if(!$result && $user->email !='access@minds.com'){

		echo "$user->username is spam! \n";
		$objects = elgg_get_entities(array('limit'=>0, 'owner_guid'=>$user->guid));
		
		foreach($objects as $object)
			$object->delete();

		$user->delete();
	} else {
		echo "$user->username is NOT spam! \n";
	}
}
	
exit;
*/

$owner = new ElggUser('ewqreops');
$offset = '';
//while(true){
$blogs = elgg_get_entities(array('subtype'=>'blog', 'limit'=>100, 'offset'=>$offset, 'owner_guid'=>$owner->guid));
$offset=end($blogs)->guid;
$threshold = 4;
foreach($blogs as $blog){
	$blog->delete();
	$guard = new minds\plugin\guard\start();
	if(!$guard->createHook('create', 'object', $blog, null)){
		echo "found spam in $blog->title\n";
		$blog->delete();
	}
}
