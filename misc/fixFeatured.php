<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

$ia = elgg_set_ignore_access();

$db = new DatabaseCall('entities_by_time');
$guids = $db->getRow('object:featured', array('offset'=>'', 'limit'=>200));

foreach($guids as $featured_id => $guid){
	$entity = get_entity($guid);
	if(!$entity){
		echo "The entity $guid doesn't exist \n";
		continue;
	}
	if($featured_id != $entity->featured_id){
		echo "issue found \n";
		$entity->featured_id = $featured_id;
		$entity->save();
		//$db = new DatabaseCall('entities_by_time');
		//$db->removeAttributes('object:featured', array($featured_id));
		//	$db->removeAttributes('object:'.$entity->subtype.':featured', array($featured_id)); 
	}
}

