<?php

require_once(dirname(dirname(__FILE__)) .'/engine/start.php');
elgg_set_ignore_access(true);
while(true){

$kaltura_videos = elgg_get_entities(array('subtype'=>'video', 'offset'=>'100000000000028064','limit'=>25));
echo "new batch (" . count($kaltura_videos) . ") \n";
foreach($kaltura_videos as $kaltura_video){

	echo "$kaltura_video->guid - Downloading\n";	
	$kaltura_server = elgg_get_plugin_setting('kaltura_server_url_api', 'archive');
	$partnerId = elgg_get_plugin_setting('partner_id', 'archive');

	
	$file = $kaltura_server . '/p/'. $partnerId . '/sp/'. $partnerId .'00/playManifest/entryId/' . $kaltura_video->kaltura_video_id . '/format/url/flavorParamId/0/video.mp4';

	echo "$kaltura_video->guid $file \n";
	file_put_contents('/tmp/'.$kaltura_video->guid, file_get_contents($file));

	$new = new minds\plugin\archive\entities\video($kaltura_video);
	$new->subtype = 'video';
	$new->upload('/tmp/'.$kaltura_video->guid);

	unlink('/tmp/'.$kaltura_video->guid);	
	$db = new minds\core\data\call('entities_by_time');	$db->removeAttributes('object:kaltura_video', array($kaltura_video->guid));

	echo $new->save() . " uploaded \n";
}
exit;
}
