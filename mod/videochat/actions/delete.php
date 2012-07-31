<?php

$ver=explode('.', get_version(true));	
if ($ver[1]>7) $guid = get_input('guid');
else		$guid = (int) get_input('videochatroom');

$videochat = get_entity($guid);

if ($videochat->canEdit()) {
	$container = $videochat->getContainerEntity();
	if ($videochat->delete()) {
		system_message(elgg_echo("videochat:deleted"));
  	$ver=explode('.', get_version(true));	
  	if ($ver[1]>7) forward("videochat/all");
  	else		forward("pg/videochat/all");
	}
}

register_error(elgg_echo("videochat:notdeleted"));
forward(REFERER);

?>
