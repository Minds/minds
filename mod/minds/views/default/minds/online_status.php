<?php

$icon_size = $vars['size'];
$user = $vars['user'];
$timeout = 60; // timeout in 1min
$last_action = $user->last_action;
$title = elgg_echo('minds:online_status:online');
if (time() - $last_action < $timeout) {
	$online_status_url = $vars['url'] . "mod/minds/graphics/online_status/online_$icon_size.png";
	echo "<img src=\"$online_status_url\" class=\"minds_online_status_$icon_size\" title=\"$title\" />";
}	
?>