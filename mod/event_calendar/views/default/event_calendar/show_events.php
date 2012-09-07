<?php
/**
 * Elgg show events view
 * 
 * @package event_calendar
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008-12
 * @link http://radagast.biz/
 * 
 */

elgg_load_library('elgg:event_calendar');

$listing_format = $vars['listing_format'];

if ($vars['events']) {
	if ($listing_format == 'agenda') {
		$vars['events'] = event_calendar_flatten_event_structure($vars['events']);
		$event_list = elgg_view('event_calendar/agenda_view',$vars);
	} else if ($listing_format == 'paged') {
		$vars['events'] = event_calendar_flatten_event_structure($vars['events']);
		$event_list = elgg_view('event_calendar/paged_view',$vars);
	} else if ($listing_format == 'full') {
		$event_list = elgg_view('event_calendar/full_calendar_view',$vars);
	} else {
		$vars['events'] = event_calendar_flatten_event_structure($vars['events']);
		$options = array(
			'list_class' => 'elgg-list-entity',
			'full_view' => FALSE,
			'pagination' => TRUE,
			'list_type' => 'listing',
			'list_type_toggle' => FALSE,
			'offset' => $vars['offset'],
			'limit' => $vars['limit'],
		);
		$event_list = elgg_view_entity_list($vars['events'], $options);
	}
} else {
	if ($listing_format == 'full') {
		// show the empty calendar
		$event_list = elgg_view('event_calendar/full_calendar_view',$vars);
	} else {
		$event_list = '<p>'.elgg_echo('event_calendar:no_events_found').'</p>';
	}
}
if ($listing_format == 'paged' || $listing_format == 'full') {
	echo $event_list;
} else {
?>
<div style="width:100%">
<div id="event_list" style="float:left;">
<?php
echo $event_list;
?>
</div>
<div style="float:right;">
<?php
echo elgg_view('event_calendar/calendar',$vars);
?>
</div>
</div>
<?php
}
