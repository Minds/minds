<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @author Mark Harding (mark@minds.com)
*/

$guid = get_input('guid');

$ob = get_entity($guid);

$filename = $ob->title . '.mp4';
$fileurl = 'http://www.minds.tv/p/100/sp/0/playManifest/entryId/' . $ob->kaltura_video_id . '/format/url/flavorParamId/10/' . $filename;

header("Pragma: public");

header("Content-type: $mime");

header("Content-Disposition: attachment; filename=\"$filename\"");

ob_clean();
flush();
readfile($fileurl);
exit;


?>
