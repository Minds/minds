<?php
/**
* Elgg file delete
* 
* @package ElggFile
*/

$guid = (int) get_input('guid');

$comment = get_entity($guid);

if (!$comment->canEdit()) {
	register_error(elgg_echo("comment:deletefailed"));
	forward(REFERRER);
}

if (!$comment->delete()) {
	register_error(elgg_echo("comment:deletefailed"));
} else {
	system_message(elgg_echo("comment:deleted"));
}

forward(REFERRER);
