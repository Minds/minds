<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

$offset = '';
while(true){
$entities = elgg_get_entities(array('subtype'=>'blog', 'limit'=>24));

foreach($entities as $entity){
	if(!$entity->getOwnerEntity(false)){
		$user = new ElggUser($entity->ownerObj);
		echo $user->save();
		//echo "our first issue with user $entity->owner_guid \n";
	//	exit;
	}
}
}
exit;
elgg_set_ignore_access(true);

$item = new ElggObject();
$item->guid = 305548043665543168;
$item->subtype = "carousel_item";
$item->title = '';
$item->access_id = ACCESS_PUBLIC;

$item->save();

