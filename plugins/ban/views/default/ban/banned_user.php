<?php
/**
 * Display a banned user in list
 */

$icon = elgg_view_entity_icon($vars['entity'], 'small');


$num_bans = elgg_get_annotations(array(
	'guid' => $vars['entity']->guid,
	'annotation_name' => 'banned',
	'annotation_calculation' => 'count',
));

$details = elgg_get_annotations(array(
	'guid' => $vars['entity']->guid,
	'annotation_name' => 'ban_release',
	'limit' => 1,
	'order' => 'n_table.time_created desc',
));
if ($details) {
	$secs_left = $details[0]->value - time();
	$hours_left = round($secs_left / 3600.0);
	if ($hours_left < 1) {
		$time_left = elgg_echo('ban:hourleft', array('<1'));
	} elseif ($hours_left < 2) {
		$time_left = elgg_echo('ban:hourleft', array('1'));
	} else {
		$time_left = elgg_echo('ban:hoursleft', array($hours_left));
	}
	$ban_date = elgg_view_friendly_time($details[0]->getTimeCreated());
} else {
	$time_left = elgg_echo('ban:forever');
	if ($num_bans == 0) {
		$num_bans = 1;
	}
	$annotation = elgg_get_annotations(array(
		'guid' => $vars['entity']->guid,
		'metadata_name' => 'banned',
		'limit' => 1,
		'order' => 'n_table.time_created desc',
	));
	if ($annotation) {
		$ban_date = elgg_view_friendly_time($annotation[0]->getTimeCreated());
	}
}

$info = <<<___END
<div class="ban-column ban-name"><b><a href="{$vars['entity']->getUrl()}">{$vars['entity']->name}</a></b></div>
<div class="ban-column ban-reason">{$vars['entity']->ban_reason}</div>
<div class="ban-column ban-count">$num_bans</div>
<div class="ban-column ban-date">$ban_date</div>
<div class="ban-column ban-release">$time_left</div>
___END;


echo elgg_view('page/components/image_block', array('image' => $icon, 'body' => $info));
