<?php

$header .= '<div class="event_calendar_agenda_date_section">';
$header .= '<div class="event_calendar_agenda_date">'.$vars['date'].'</div>';
$header .= '<table><thead><tr>';
$header .= '<th class="agenda_header">'.elgg_echo('event_calendar:agenda:column:time').'</th>';
$header .= '<th class="agenda_header">'.elgg_echo('event_calendar:agenda:column:session').'</th>';
$header .= '<th class="agenda_header">'.elgg_echo('event_calendar:agenda:column:venue').'</th>';
$header .= '</td></thead><tbody>';

echo $header;
?>