<?php

$ver=explode('.', get_version(true));	
if ($ver[1]>7) $guid = get_input('guid');
else		$guid = (int) get_input('videoconferenceroom');

$videoconference = get_entity($guid);

if ($videoconference->canEdit()) {
	$container = $videoconference->getContainerEntity();
	if ($videoconference->delete()) {
		system_message(elgg_echo("videoconference:deleted"));
  	$ver=explode('.', get_version(true));	
  	if ($ver[1]>7) forward("videoconference/all");
  	else		forward("pg/videoconference/all");
	}
}

register_error(elgg_echo("videoconference:notdeleted"));
forward(REFERER);

?>
