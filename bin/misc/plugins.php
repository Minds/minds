<?php

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");


$db = new Minds\Core\Data\Call('plugin');
$plugins = array('channel', 'thumbs', 'payments', 'blog', 'guard', 'notifications', 'groups', 'gatherings', 'archive', 'search', 'comments');
foreach($plugins as $plugin){
  $db->insert($plugin, array('type'=>'plugin', 'active'=>1, 'access_id'=>2));
}

$search = new minds\plugin\search\start(array('active'=>true));
foreach(\elgg_get_entities(array('type'=>'user','limit'=>500)) as $entity){
///	if($entity->access_id == 2)
		$search->createDocument($entity);
}
foreach(\elgg_get_entities(array('type'=>'object','limit'=>500)) as $entity){
	if($entity->access_id == 2)
		$search->createDocument($entity);
}
