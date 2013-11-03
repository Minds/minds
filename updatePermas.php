<?php

require('engine/start.php');

$blogs = elgg_get_entities(array('subtype'=>'blog', 'limit'=>1000000));
foreach($blogs as $blog){

	$perma = $blog->getURL();
	$blog->perma_url = $perma;
	$blog->save();
}
