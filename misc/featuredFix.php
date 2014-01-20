<?php

require('/var/www/elgg/engine/start.php');

$offset ="";

while(1) {
$user = get_user_by_username('mark');
$featured = elgg_get_entities(array('type'=>'object', 'subtype'=>'album','offset'=>$offset, 'limit'=>200));

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
