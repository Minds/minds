<?php

$ver=explode('.', get_version(true));	
if ($ver[1]>7) $guid = get_input('guid');
else		$guid = (int) get_input('livestreamingroom');

$livestreaming = get_entity($guid);

if ($livestreaming->canEdit()) {
	$container = $livestreaming->getContainerEntity();
	if ($livestreaming->delete()) {
		system_message(elgg_echo("livestreaming:deleted"));
  	$ver=explode('.', get_version(true));	
  	if ($ver[1]>7) forward("livestreaming/all");
  	else		forward("pg/livestreaming/all");
	}
}

register_error(elgg_echo("livestreaming:notdeleted"));
forward(REFERER);

?>
