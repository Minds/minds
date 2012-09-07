<?php
if ($vars['mode']) {
	$mode = $vars['mode'];
} else {
	$mode = 'month';
}

# event_calendar/list/<start_date>/<display_mode>/<filter_context>/<region>
if ($vars['group_guid']) {
	$link_bit = $vars['url']."event_calendar/group/{$vars['group_guid']}/{$vars['original_start_date']}/%s";
} else {
	$link_bit = $vars['url']."event_calendar/list/{$vars['original_start_date']}/%s/{$vars['filter']}";
}

#$link_bit = "event_calendar/show_events.php?start_date='.$vars['original_start_date'].'&group_guid='.$vars['group_guid'].'&filter='.$vars['filter'].'&mode=';

$range_bit = '';
$first_date = $vars['first_date'];
if ($first_date) {
	$range_bit .= 'minDate: $.datepicker.parseDate("yy-mm-dd", "'.$first_date.'"),'."\n";
}
$last_date = $vars['last_date'];
if ($last_date) {
	$range_bit .= 'maxDate: $.datepicker.parseDate("yy-mm-dd", "'.$last_date.'"),'."\n";
}
if ($first_date || $last_date) {
	if (substr($first_date,0,7) == substr($last_date,0,7)) {
		$range_bit .= "changeMonth: false,\n";
	}
	
	if (substr($first_date,0,4) == substr($last_date,0,4)) {
		$range_bit .= "changeYear: false,\n";
	}
}

$body .= elgg_view("input/datepicker_inline",
		array(
			'name' 	=> 'my_datepicker',
			'mode' 			=> $vars['mode']?$vars['mode']:'month',
			'start_date' 	=> $vars['start_date'],
			'end_date' 		=> $vars['end_date'],
			'group_guid'	=> $vars['group_guid'],
			'range_bit'		=> $range_bit,
		)
);

$body .= '<div id="calendarmenucontainer">';
$body .= '<ul id="calendarmenu">';
if ($mode == 'day') {
	$link_class = ' class="sys_selected"';
} else {
	$link_class = '';
}
$body .= '<li'.$link_class.'><a href="'.sprintf($link_bit,'day').'">'.elgg_echo('event_calendar:day_label').'</a></li>';
if ($mode == 'week') {
	$link_class = ' class="sys_selected"';
} else {
	$link_class = '';
}
$body .= '<li'.$link_class.'><a href="'.sprintf($link_bit,'week').'">'.elgg_echo('event_calendar:week_label').'</a></li>';
if ($mode == 'month') {
	$link_class = ' class="sys_selected sys_calmenu_last"';
} else {
	$link_class = ' class="sys_calmenu_last"';
}
$body .= '<li'.$link_class.'><a href="'.sprintf($link_bit,'month').'">'.elgg_echo('event_calendar:month_label').'</a></li>';
$body .= '</ul>';
$body .= '</div>';
echo $body;
?>