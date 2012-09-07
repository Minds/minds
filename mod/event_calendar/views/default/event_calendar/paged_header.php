<?php
$header .= '<div class="event_calendar_paged_header_section">';
$header .= '<div class="event_calendar_paged_month">'.$vars['date'].'</div>';
$header .= '<table class="event_calendar_paged_table"><thead><tr>';
$header .= '<th class="paged_header">'.elgg_echo('event_calendar:paged:column:date').'</th>';
$header .= '<th class="paged_header">'.elgg_echo('event_calendar:paged:column:time').'</th>';
$header .= '<th class="paged_header">'.elgg_echo('event_calendar:paged:column:event').'</th>';
$header .= '<th class="paged_header">'.elgg_echo('event_calendar:paged:column:venue').'</th>';
if ($vars['personal_manage'] != 'no') {
	$header .= '<th class="paged_header">'.elgg_echo('event_calendar:paged:column:calendar').'</th>';
}
$header .= '</td></thead><tbody>';

echo $header;
?>