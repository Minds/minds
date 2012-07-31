<?php

$ver=explode('.', get_version(true));	
if ($ver[1]>7) $guid = get_input('guid');
else		$guid = (int) get_input('videoconsultationroom');

$videoconsultation = get_entity($guid);

if ($videoconsultation->canEdit()) {
	$container = $videoconsultation->getContainerEntity();
	if ($videoconsultation->delete()) {
		system_message(elgg_echo("videoconsultation:deleted"));
  	$ver=explode('.', get_version(true));	
  	if ($ver[1]>7) forward("videoconsultation/all");
  	else		forward("pg/videoconsultation/all");
	}
}

register_error(elgg_echo("videoconsultation:notdeleted"));
forward(REFERER);

?>
