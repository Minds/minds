<?php
/**
 * User quota
 */

$user = elgg_get_logged_in_user_entity();


$quota = elgg_get_plugin_setting('quota', 'tidypics');
if ($quota) {
	$image_repo_size = (int)$user->image_repo_size;
	$image_repo_size = $image_repo_size / 1024 / 1024;
	$quote_percentage = round(100 * ($image_repo_size / $quota));
	// for small quotas, so one decimal place
	if ($quota < 10) {
		$image_repo_size = sprintf('%.1f', $image_repo_size);
	} else {
		$image_repo_size = round($image_repo_size);
	}
	if ($image_repo_size > $quota) {
		$image_repo_size = $quota;
	}

	$title = elgg_echo('tidypics:title:quota');
	$body = elgg_echo("tidypics:quota") . ' ' . $image_repo_size . '/' . $quota . " MB ({$quote_percentage}%)";
	echo elgg_view_module('aside', $title, $body);
}
