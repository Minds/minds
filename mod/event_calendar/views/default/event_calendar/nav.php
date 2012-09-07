<?php
$allselect = ''; $friendsselect = ''; $mineselect = '';
switch($vars['filter']) {
	case 'all':		$allselect = 'class="selected"';
					break;
	case 'friends':		$friendsselect = 'class="selected"';
					break;
	case 'mine':		$mineselect = 'class="selected"';
					break;
	case 'open':		$openselect = 'class="selected"';
					break;
}

$url_start = $vars['url'].'mod/event_calendar/show_events.php?group_guid='.$vars['group_guid'].'&amp;mode='.$vars['mod'].'&amp;start_date='.$vars['start_date'];

?>
<div id="elgg_horizontal_tabbed_nav">
	<ul>
<?php
$event_calendar_spots_display = get_plugin_setting('spots_display', 'event_calendar');
if ($event_calendar_spots_display == "yes") {
?>
		<li <?php echo $openselect; ?> ><a onclick="javascript:$('#event_list').load('<?php echo $url_start; ?>&amp;filter=open&amp;callback=true'); return false;" href="<?php echo $url_start; ?>&amp;filter=open&amp;callback=true"><?php echo elgg_echo('event_calendar:open'); ?></a></li>
<?php
}
?>
		<li <?php echo $allselect; ?> ><a onclick="javascript:$('#event_list').load('<?php echo $url_start; ?>&amp;filter=all&amp;callback=true'); return false;" href="<?php echo $url_start; ?>&amp;filter=all&amp;callback=true"><?php echo elgg_echo('all'); ?></a></li>
		<li <?php echo $friendsselect; ?> ><a onclick="javascript:$('#event_list').load('<?php echo $url_start; ?>&amp;filter=friends&amp;callback=true'); return false;" href="<?php echo $url_start; ?>&amp;filter=friends&amp;callback=true"><?php echo elgg_echo('friends'); ?></a></li>
		<li <?php echo $mineselect; ?> ><a onclick="javascript:$('#event_list').load('<?php echo $url_start; ?>&amp;filter=mine&amp;callback=true'); return false;" href="<?php echo $url_start; ?>&amp;filter=mine&amp;callback=true"><?php echo elgg_echo('event_calendar:mine'); ?></a></li>
	</ul>
</div>
<?php
$event_calendar_region_display = get_plugin_setting('region_display', 'event_calendar');
if ($event_calendar_region_display == 'yes') {
	$url_start .= '&amp;filter='.$vars['filter'];
	echo elgg_view('event_calendar/region_select',array('url_start'=>$url_start,'region'=>$vars['region']));
}
?>