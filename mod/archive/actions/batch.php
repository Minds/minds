<?php
$batch_guid = get_input('batch_guid');
$album_guid = get_input('album_guid');

$batch = new minds\plugin\archive\entities\batch($batch_guid);

$guids = $batch->getList();

/**
 * Run a batch command to append this guids to the container index
 */
$index = new minds\core\data\indexes('object:container');
$index->set($album_guid, $guids);

/**
 * Now update the container guid for each entity (we are not loading the entities here as we don't want to read, just write)
 */
$db = new minds\core\data\call('entities');
foreach($guids as $guid){
	$db->insert($guid, array('container_guid'=>$album_guid));
}
exit;