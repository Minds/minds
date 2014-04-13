<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

elgg_set_ignore_access();

$db = new DatabaseCall('entities_by_time');

$options = array(
		'timespan' => get_input('timespan', 'day')
	);
$limit=30;
$offset ="";
$namespace = 'object';
while(true){
	$trending = new MindsTrending(array(), $options);
	$guids = $trending->getList(array('limit'=>$limit, 'offset'=>$offset));
	$entities = elgg_get_entities(array('type'=>'object', 'guids'=>$guids));
	$offset = count($guids);
	foreach($entities as $entity){

		try{
		if(!$entity instanceof ElggEntity)	{

			continue;

		}
		
		$owner = $entity->getOwnerEntity();
		if(!$owner){
			echo "$entity->guid does not have an owner! \n";
			continue;
		}
		//var_dump( $entity->getOwnerEntity(true));
		$entity->save();

		if(isset($entity->ownerObj))
		echo "denormalised $entity->guid \n";
		} catch(Exception $e){
		}
	}


}
