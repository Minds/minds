<?php

$region_list = trim(elgg_get_plugin_setting('region_list', 'event_calendar'));
// make sure that we are using Unix line endings
$region_list = str_replace("\r\n","\n",$region_list);
$region_list = str_replace("\r","\n",$region_list);
if ($region_list) {
	$body = '';
	$options_values = array('-' =>elgg_echo('event_calendar:all'));
	foreach(explode("\n",$region_list) as $region_item) {
		$region_item = trim($region_item);
		$options_values[$region_item] = $region_item;
	}
	
	$body .= elgg_echo('event_calendar:region_filter_by_label');
	$body .= elgg_view('input/hidden',array('id'=>'event-calendar-region-url-start','value'=>$vars['url_start']));
	$body .= elgg_view("input/dropdown",array('id' => 'event-calendar-region','value'=>$vars['region'],'options_values'=>$options_values));
	$body .= '<br />';
}

echo $body;
