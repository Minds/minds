<?php

require('/var/www/minds-multisite/docroot/engine/start.php');

$offset ="";

while(1) {
$featured = elgg_get_entities(array('type'=>'object', 'subtype'=>'image','offset'=>$offset, 'limit'=>200));

$db = new DatabaseCall('entities_by_time');
foreach($featured as $entity){
	elgg_set_ignore_access();
	if($entity->featured == 1){
		echo "$entity->guid \n";
		$db->insert('object:featured', array($entity->featured_id => $entity->getGUID()));
		$db->insert('object:'.$entity->subtype.':featured', array($entity->featured_id => $entity->getGUID()));
	}
}
$offset = end($featured)->guid;
}
