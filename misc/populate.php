<?php

require_once(dirname(__FILE__) . "/engine/start.php");

$limit = 200;

$i = 2;

while($i < $limit){
	$i++;

	$blog = new ElggBlog();
	$blog->title = 'Blog: ' + $i;
	$blog->description = "this is just a test";	
	$blog->owner_guid = elgg_get_logged_in_user_guid();
	$blog->license = 'publicdomain';
	$blog->access_id = 2;
	$blog->status = 'published';
	$blog->save();
	sleep(1);
}
