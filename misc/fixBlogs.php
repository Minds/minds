<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access(true);

$blogs = elgg_get_entities(array('subtype'=>'blog', 'limit'=>200));
foreach($blogs as $blog){

	if(!$blog->getOwnerEntity(false)->username){
		echo "$blog->guid has no owner...";
		$blog->delete();
	}

}

exit;

$blog = new ElggBlog(338520225441910784);
$blog->save();
exit;


//reset_login_failure_count($john->guid);
$user = new ElggUser('culture');

$user->icontime = time();
//var_dump(force_user_password_reset($user->guid, 'temp123'));
//var_dump($user);
//$user->makeAdmin();
//$user->enable();
$user->save();


