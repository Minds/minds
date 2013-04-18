<?php

$guid = get_input('guid');

$entity = get_entity($guid);

if($entity->getSubtype() == 'kaltura_video'){
	elgg_load_librar('archive:kaltura');
	$kaltura_server = elgg_get_plugin_setting('kaltura_server_url',  'kaltura_video');
	$partnerId = elgg_get_plugin_setting('partner_id', 'kaltura_video');
	
	$filename = urlencode($ob->title) . '.mp4';
	$file = $kaltura_server . '/p/'. $partnerId . '/sp/'. $partnerId .'00/playManifest/entryId/' . $ob->kaltura_video_id . '/format/url/flavorParamId/10/' . $filename;
	$mime = "video/mp4";
} elseif($entity->getSubtype() == 'file'){
	$filename = $entity->originalfilename;
	$mime = $entity->getMimeType();
	if (!$mime) {
		$mime = "application/octet-stream";
	}
	$file = $entity->getFilenameOnFilestore();
}


header("Pragma: public");

header("Content-type: $mime");

header("Content-Disposition: attachment; filename=\"$filename\"");

ob_clean();
flush();
readfile($file);
exit;


?>
