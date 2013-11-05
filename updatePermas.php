<?php

require('engine/start.php');

$blogs = elgg_get_entities(array('subtype'=>'blog', 'limit'=>1000000));
echo "now running save loop";
foreach($blogs as $blog){

	$perma = $blog->getURL();
	$blog->perma_url = $perma;
	echo "updated " . $blog->save();
	
}
