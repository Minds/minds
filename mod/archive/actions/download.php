<?php

$guid = get_input('guid');

$entity = new ElggFile($guid); 
	$filename = $entity->originalfilename;
	$mime = $entity->getMimeType();
	if (!$mime) {
		$mime = "application/octet-stream";
	}
	$file = $entity->getFilenameOnFilestore();


header("Pragma: public");

header("Content-type: $mime");

header("Content-Disposition: attachment; filename=\"$filename\"");

ob_clean();
flush();
readfile($file);
exit;


?>
