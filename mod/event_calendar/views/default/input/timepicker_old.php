<?php

$time_format = elgg_get_plugin_setting('timeformat', 'event_calendar');
if (!$time_format) {
	$time_format = 24;
}

$value = $vars['value'];
if (is_numeric($value)) {
	$hour = floor($value/60);
	$minute = ($value -60*$hour);
	
	// add 1 to avoid pulldown 0 bug
	$hour++;
	$minute++;
} else {
	$hour = '-';
	$minute = '-';
}

$hours = array();
$hours['-'] = '-';

for($i=0;$i<$time_format;$i++) {
	$hours[$i+1] = $i;
}

$minutes = array();
$minutes['-'] = '-';

for($i=0;$i<60;$i=$i+15) {
	$minutes[$i+1] = sprintf("%02d",$i);
}

echo elgg_view('input/dropdown',array('name'=>$vars['name'].'_h','value'=>$hour,'options_values'=>$hours));
echo elgg_view('input/dropdown',array('name'=>$vars['name'].'_m','value'=>$minute,'options_values'=>$minutes));

?>