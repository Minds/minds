<?php


if (isset($vars['entity']) && elgg_instanceof($vars['entity'],'object','webinar')) {
	$help_text = elgg_echo('webinar:status');
	$class = "elgg-status";
	$string = elgg_echo("webinar:status:{$vars['entity']->status}");
	echo "<span title=\"$help_text\" class=\"$class\">$string</span>";
}