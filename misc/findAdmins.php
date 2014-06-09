<?php

require(dirname(dirname(__FILE__)).'/engine/start.php');
elgg_set_ignore_access(true);

$entities = new minds\core\data\call('entities_by_time');
$usersCount = $entities->countRow('user');
$offset = '';
$scanned= 0;
while($scanned < $usersCount){
	$users = elgg_get_entities(array('type'=>'user', 'limit'=>500, 'offset'=>$offset, 'newest_first'=>false));
	$offset =end($users)->guid;
	$scanned = $scanned + count($users);
	foreach($users as $user){

		if($user->isAdmin()){
			$admins[$user->name] = $user->username;
		}
	}
	echo "scanned $scanned / $usersCount ($offset)\n";
}

var_dump($admins);	
exit;

$offset = '';

//while(true){
$blogs = elgg_get_entities(array('subtype'=>'blog', 'limit'=>100, 'offset'=>$offset));
$offset=end($blogs)->guid;
$threshold = 4;
foreach($blogs as $blog){
	$guard = new minds\plugin\guard\start();
	if(!$guard->createHook('create', 'object', $blog, null)){
		echo "found spam in $blog->title\n";
		$blog->delete();
	}
}
