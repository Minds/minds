<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @author Mark Harding (mark@minds.com)
*/

$guid = get_input('guid');

$ob = get_entity($guid);

$kaltura_server = elgg_get_plugin_setting('kaltura_server_url',  'kaltura_video');
$partnerId = elgg_get_plugin_setting('partner_id', 'kaltura_video');

$filename = urlencode($ob->title) . '.mp4';
$fileurl = $kaltura_server . '/p/'. $partnerId . '/sp/'. $partnerId .'00/playManifest/entryId/' . $ob->kaltura_video_id . '/format/url/flavorParamId/10/' . $filename;

header("Pragma: public");

header("Content-type: $mime");

header("Content-Disposition: attachment; filename=\"$filename\"");

ob_clean();
flush();
readfile($fileurl);
exit;


?>
