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

/**
 * and add a river feed
 */

$river = new ElggRiverItem(array(
	'to_guid' => get_input('container_guid'),
	'subject_guid' => elgg_get_logged_in_user_guid(),
	//'body' => $message,
	'view' => 'river/object/album/batch',
	'object_guid' => $album_guid,
	'batch_guids' => json_encode($guids),
	'batch_count' => count($guids)
));
$river->save();

exit;