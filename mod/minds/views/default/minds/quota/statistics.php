<?php
/**
 * QUOTA
 */
 
$user = elgg_get_logged_in_user_entity();

$storage_label =  elgg_echo("minds:quota:statisitcs:storage");
$bytes = $user->quota_storage;
$gb = (($bytes /1024) /1024) /1024;
$storage = round($gb,2) . " GB's";
$bandwidth_label =  elgg_echo("minds:quota:statisitcs:bandwidth");
$bandwidth = "0 GB's";

$title = elgg_echo("minds:quota:statisitcs:title");

$content = <<<__HTML
<table class="elgg-table-alt">
	<tr class="odd">
		<td class="column-one">$storage_label</td>
		<td>$storage</td>
	</tr>
	<tr class="even">
		<td class="column-one">$bandwidth_label</td>
		<td>$bandwidth</td>
	</tr>
</table>
__HTML;

echo elgg_view_module('info', $title, $content);

