<?php

$time_format = '24';

$value = $vars['value'];
if (is_numeric($value)) {
	$hour = floor($value/60);
	$minute = 5 * round(($value -60*$hour) / 5);
} else {
	$hour = 0;
	$minute = 0;
}

$hours = array();
$minutes = array();

if ($time_format == '12') {
	$meridians = array('am'=>'am','pm'=>'pm');
	if ($hour == 0) {
		$hour = 12;
		$meridian = 'am';
	} else if ($hour == 12) {
		$meridian = 'pm';
	} else if ($hour < 12) {
		$meridian = 'am';
	} else {
		$hour -= 12;
		$meridian = 'pm';
	}
	for($h=1;$h<=12;$h++) {
		$hours[$h] = $h;
	}
} else {
	for($h=0;$h<=23;$h++) {
		$hours[$h] = $h;
	}
}	

for($m=0;$m<60;$m=$m+5) {
	$mt = sprintf("%02d",$m);
	$minutes[$m] = $mt;
}

echo elgg_view('input/dropdown',array('name'=>$vars['name'].'_hour','value'=>$hour,'options_values'=>$hours));
echo " <b>:</b> ";
echo elgg_view('input/dropdown',array('name'=>$vars['name'].'_minute','value'=>$minute,'options_values'=>$minutes));
if ($time_format == '12') {
	echo elgg_view('input/dropdown',array('name'=>$vars['name'].'_meridian','value'=>$meridian,'options_values'=>$meridians));
}
