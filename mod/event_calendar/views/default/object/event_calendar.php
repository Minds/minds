<?php

/**
 * Elgg event_calendar object view
 * 
 * @package event_calendar
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008
 * @link http://radagast.biz/
 * 
 */

elgg_load_library('elgg:event_calendar');

$event = $vars['entity'];
$full = elgg_extract('full_view', $vars, FALSE);

if ($full) {
	$body = elgg_view('event_calendar/strapline',$vars);
	if ($event->web_conference) {
		$body .= '<br />';
		$body .= elgg_view('event_calendar/conference_button',array('event'=>$event));
	}
	$event_items = event_calendar_get_formatted_full_items($event);
	$body .= '<br />';
	
	foreach($event_items as $item) {
		$value = $item->value;
		if (!empty($value)) {
				
			//This function controls the alternating class
			$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
			$body .= "<p class=\"{$even_odd}\"><b>";
			$body .= $item->title.':</b> ';
			$body .= $item->value;
		}
	}
	$metadata = elgg_view_menu('entity', array(
		'entity' => $event,
		'handler' => 'event_calendar',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
	
	$tags = elgg_view('output/tags', array('tags' => $event->tags));
	
	$params = array(
		'entity' => $event,
		'metadata' => $metadata,
		'tags' => $tags,
		'title' => false,
	);
	$list_body = elgg_view('object/elements/summary', $params);
	echo $list_body;
	echo $body;
	if ($event->long_description) {
		echo '<p>'.$event->long_description.'</p>';
	} else {
		echo '<p>'.$event->description.'</p>';
	}
	if ($vars['light_box'] == TRUE) {
		$event_calendar_add_users = elgg_get_plugin_setting('add_users', 'event_calendar');
		if ($event_calendar_add_users == 'yes') {
			$url =  "event_calendar/manage_users/$event->guid";
			echo '<p>'.elgg_view('output/url',array('text'=> elgg_echo('event_calendar:manage_users:breadcrumb'), 'href'=>$url)).'</p>';
		}
	}
	if (elgg_get_plugin_setting('add_to_group_calendar', 'event_calendar') == 'yes') {
		echo elgg_view('event_calendar/forms/add_to_group',array('event' => $event));
	}
	
} else {
	
	$time_bit = event_calendar_get_formatted_time($event);
	$icon = '<img src="'.elgg_view("icon/object/event_calendar/small").'" />';
	$extras = array($time_bit);
	if ($event->description) {
		$extras[] = $event->description;
	}
	
	if ($event_calendar_venue_view = elgg_get_plugin_setting('venue_view', 'event_calendar') == 'yes') {
		$extras[] = $event->venue;
	}
	if ($extras) {
		$info = "<p>".implode("<br />",$extras)."</p>";
	} else {
		$info = '';
	}
	
	if (elgg_in_context('widgets')) {
		$metadata = '';
	} else {
		$metadata = elgg_view_menu('entity', array(
			'entity' => $event,
			'handler' => 'event_calendar',
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz',
		));
	}
	
	$tags = elgg_view('output/tags', array('tags' => $event->tags));
	
	$params = array(
		'entity' => $event,
		'metadata' => $metadata,
		'subtitle' => $info,
		'tags' => $tags,
	);
	$list_body = elgg_view('object/elements/summary', $params);
	
	echo elgg_view_image_block($icon, $list_body);
}

?>