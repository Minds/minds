<?php

require(dirname(dirname(__FILE__)).'/engine/start.php');
elgg_set_ignore_access(true);

/*$groups = minds\core\entities::get(array('type'=>'group', 'limit'=>1000));
foreach($groups as $group){

	if(!$group->name && !$group->title){
		$group->delete();
	} else {
		echo "keeping $group->name \n";
	}
}
exit;
*/
/*try{
$db = new minds\core\data\call();
$db->getCF('session')->truncate();
}catch(Exception $e){}
*/
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
$offset = '';
//while(true){
$blogs = elgg_get_entities(array('subtype'=>'blog', 'limit'=>500, 'offset'=>$offset));
$offset=end($blogs)->guid;
$threshold = 4;
foreach($blogs as $blog){
//	$blog->delete();
	$guard = minds\core\plugins::factory('guard');
	if(!$guard->createHook('create', 'object', $blog, null)){
		echo "found spam in $blog->title\n";
		$blog->delete();
	}
}
