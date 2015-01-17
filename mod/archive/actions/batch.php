<?php
$batch_guid = get_input('batch_guid');
$album_guid = get_input('album_guid');


$batch = new minds\plugin\archive\entities\batch($batch_guid);
$container = get_entity(get_input('container_guid')); //@todo use a new OOP method

$guids = $batch->getList();

/**
 * Run a batch command to append this guids to the container index
 */
$index = new Minds\Core\data\indexes('object:container');
$index->set($album_guid, $guids);

/**
 * Now update the container guid for each entity (we are not loading the entities here as we don't want to read, just write)
 */
$db = new Minds\Core\Data\Call('entities');
$data = array('container_guid'=>$album_guid);
if($container instanceof ElggGroup){
	$data['access_id'] = $container->guid;
	$guids[$album_guid] = $album_guid;
}
foreach($guids as $guid){
	$db->insert($guid, $data);
}

if(!$guids || empty($guids))
	exit;

/**
 * and add a river feed
 */
//$river = new ElggRiverItem($params);
//$river->save();
$images = array();
$i = 0;
foreach($guids as $guid){
	if($i == 3)
		continue;
	
	$i++;
	
	$images[] = array(
		'src' => elgg_get_site_url() . 'archive/thumbnail/'.$guid,
		'href' => elgg_get_site_url() . 'archive/view/'.$album_guid.'/'.$guid,
	);
}

$activity  = new \minds\entities\activity();
$activity->setCustom('batch', $images)
		->setMessage('Added '. count($guids) . ' new images. <a href="'.elgg_get_site_url().'archive/view/'.$album_guid.'">View</a>')
		->save();

exit;
