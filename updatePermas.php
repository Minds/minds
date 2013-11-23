<?php

require('engine/start.php');

$blog = get_entity(35498);
$blog->perma_url = "http://www.minds.com/blog/view/35498/salt-water-fuel";
$blog->save();
exit;

$blogs = elgg_get_entities(array('subtype'=>'blog', 'limit'=>1000000));
echo "now running save loop";
foreach($blogs as $blog){

	$perma = $blog->getURL();
	$blog->perma_url = $perma;
	echo "updated " . $blog->save();
	
}
