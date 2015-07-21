<?php
/**
 * Features an item and adds it to elasticsearch
 * 
 */

admin_gatekeeper();
global $CONFIG;

$guid = get_input('guid');
$entity = get_entity($guid, 'object'); //always an object, unless we decide to feature channels...

if(!$entity->featured_id || $entity->featured_id == 0){

	$entity->feature();

    $newsfeed = true;
    //add_to_river('river/object/'.$entity->getSubtype().'/feature', 'feature', $entity->getOwnerGUID(), $entity->getGuid(), 2, time(), NULL, array('feature'));
	$activity = new minds\entities\activity();
    if($entity->subtype == 'blog'){
        $activity->setTitle($entity->title)
			->setBlurb($entity->excerpt)
			->setUrl($entity->getURL())
            ->setThumbnail($entity->getIconURL());
    }elseif($entity->subtype == 'video'){
        $activity->setFromEntity($entity)
                ->setCustom('video', array(
                    'thumbnail_src'=>$entity->getIconUrl(),
                    'guid'=>$entity->guid))
                ->setTitle($entity->title)
                ->setBlurb($entity->description);
    }elseif($entity->subtype == 'image'){
        $activity->setFromEntity($entity)
                   ->setCustom('batch', array(array('src'=>elgg_get_site_url() . 'archive/thumbnail/'.$entity->guid, 'href'=>elgg_get_site_url() . 'archive/view/'.$entity->container_guid.'/'.$entity->guid)))
                    ->setTitle($entity->title);
    }else{
        $newsfeed = false;
    }

    if($newsfeed){
	    $activity->owner_guid = $entity->owner_guid;		
	    $activity->indexes = array('activity:featured');
	    $activity->save();
    }
	
	system_message(elgg_echo("Featured..."));
	
	echo 'featured';

	//Send notification Chris

	$to_guid = $entity->getOwnerGuid();
	$user = get_user_by_username('minds');

	\elgg_trigger_plugin_hook('notification', 'all', array(
        'to' => array($to_guid),
        'from'=> 100000000000000519,
				'object_guid'=>$guid,
				'description'=>$message,
				'notification_view'=>'feature'
			));
}else{

	$entity->unFeature();
	
	system_message(elgg_echo("Un-featured..."));
	
	echo 'un-featured';

}
$entity->save();

