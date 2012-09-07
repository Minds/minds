<?php
/**
 * Elgg event model
 *
 * @package event_calendar
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008-2011
 * @link http://radagast.biz/
 *
 */

function event_calendar_get_event_for_edit($event_id) {
	if ($event_id && $event = get_entity($event_id)) {
		if ($event->canEdit()) {
			return $event;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

// converts to time in minutes since midnight
function event_calendar_convert_to_time($hour,$minute,$meridian) {
	if ($meridian) {
		if ($meridian == 'am') {
			if ($hour == 12) {
				$hour = 0;
			}
		} else {
			if ($hour < 12) {
				$hour += 12;
			}
		}
	}
	return 60*$hour+$minute;
}

// returns the event or FALSE
function event_calendar_set_event_from_form($event_guid,$group_guid) {

	$event_calendar_times = elgg_get_plugin_setting('times', 'event_calendar');
	$event_calendar_region_display = elgg_get_plugin_setting('region_display', 'event_calendar');
	$event_calendar_type_display = elgg_get_plugin_setting('type_display', 'event_calendar');
	$event_calendar_spots_display = elgg_get_plugin_setting('spots_display', 'event_calendar');
	$event_calendar_hide_end = elgg_get_plugin_setting('hide_end', 'event_calendar');
	$event_calendar_more_required = elgg_get_plugin_setting('more_required', 'event_calendar');
	$event_calendar_personal_manage = elgg_get_plugin_setting('personal_manage', 'event_calendar');
	$event_calendar_repeating_events = elgg_get_plugin_setting('repeating_events', 'event_calendar');
	$schedule_type = get_input('schedule_type');

	if ($event_calendar_more_required == 'yes') {
		$required_fields = array('title','venue','start_date',
			'brief_description','fees','contact','organiser',
			'tags');

		if ($event_calendar_times != 'no') {
			$required_fields[] = 'start_time';
			if ($event_calendar_hide_end != 'yes') {
				$required_fields[] = 'end_time';
			}
		}
		if ($event_calendar_region_display == 'yes') {
			$required_fields[] = 'region';
		}
		if ($event_calendar_type_display == 'yes') {
			$required_fields[] = 'event_type';
		}
		if ($event_calendar_spots_display == 'yes') {
			$required_fields[] = 'spots';
		}
	} else {
		$required_fields = array('title');
	}

	if ($event_guid) {
		$event = get_entity($event_guid);
		if (!elgg_instanceof($event, 'object', 'event_calendar')) {
			// do nothing because this is a bad event guid
			return FALSE;
		}
	} else {
		$user_guid = elgg_get_logged_in_user_guid();
		$event = new ElggObject();
		$event->subtype = 'event_calendar';
		$event->owner_guid = $user_guid;
		if ($group_guid) {
			$event->container_guid = $group_guid;
		} else {
			$event->container_guid = $event->owner_guid;
		}
	}
	$event->access_id = get_input('access_id');
	$event->title = get_input('title');
	$event->description = get_input('description');
	$event->venue = get_input('venue');

	if ($schedule_type != 'poll') {
		$start_date_text = trim(get_input('start_date'));
		/*$event->original_start_date = get_input('start_date');
		//$end_date = trim(get_input('end_date',''));
		// convert start date from current server time to GMT
		$start_date_text = gmdate("Y-m-d",$start_date);
		//$event->munged_start_date_string = $start_date_text." ".date_default_timezone_get();*/
		
		// TODO: is the timezone bit necessary?
		$event->start_date = strtotime($start_date_text." ".date_default_timezone_get());
		$end_date_text = trim(get_input('end_date',''));
		//$event->original_end_date = get_input('end_date');
		if ($end_date_text) {	
			$event->end_date = strtotime($end_date_text." ".date_default_timezone_get());
			//$event->munged_end_date_string = $end_date_text." ".date_default_timezone_get();
		} else {
			$event->end_date = '';
		}
	
		if ($event_calendar_times != 'no') {
			$hour = get_input('start_time_hour','');
			$minute = get_input('start_time_minute','');
			$meridian = get_input('start_time_meridian','');
			if (is_numeric($hour) && is_numeric($minute)) {
				$event->start_time = event_calendar_convert_to_time($hour,$minute,$meridian);
			} else {
				$event->start_time = '';
			}
			$hour = get_input('end_time_hour','');
			$minute = get_input('end_time_minute','');
			$meridian = get_input('end_time_meridian','');
			if (is_numeric($hour) && is_numeric($minute)) {
				$event->end_time = event_calendar_convert_to_time($hour,$minute,$meridian);
			} else {
				$event->end_time = '';
			}
			if (is_numeric($event->start_date) && is_numeric($event->start_time)) {
				// Set start date to the Unix start time, if set.
				// This allows sorting by date *and* time.
				$event->start_date += $event->start_time*60;
			}
		}
	}
	if ($event_calendar_spots_display == 'yes') {
		$event->spots = trim(get_input('spots'));
	}
	if ($event_calendar_region_display == 'yes') {
		$event->region = get_input('region');
	}
	if ($event_calendar_type_display == 'yes') {
		$event->event_type = get_input('event_type');
	}
	if ($event_calendar_personal_manage == 'by_event') {
		$event->personal_manage = get_input('personal_manage');
	}
	
	if ($event_calendar_repeating_events != 'no') {
		$repeats = get_input('repeats');
		$event->repeats = $repeats;
		if ($repeats == 'yes') {
			$event->repeat_interval = get_input('repeat_interval');
			$dow = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
			foreach ($dow as $w) {
				$v = 'event-calendar-repeating-'.$w.'-value';
				$event->$v = get_input($v);
			}
		}
	}
	$event->fees = get_input('fees');
	$event->contact = get_input('contact');
	$event->organiser = get_input('organiser');
	$event->tags = string_to_tag_array(get_input('tags'));
	$event->long_description = get_input('long_description');
	$event->schedule_type = $schedule_type;
	$event->send_reminder = get_input('send_reminder');
	$event->reminder_number = get_input('reminder_number');
	$event->reminder_interval = get_input('reminder_interval');
	$event->web_conference = get_input('web_conference');
	$event->real_end_time = event_calendar_get_end_time($event);
	foreach ($required_fields as $fn) {
		if (!trim($event->$fn)) {
			return FALSE;
			break;
		}
	}
	if ($event->save()) {
		if (!$event_guid && $event->web_conference) {
			if (!event_calendar_create_bbb_conf($event)) {
				register_error(elgg_echo('event_calendar:conference_create_error'));
			}
		}
		if ($group_guid && (elgg_get_plugin_setting('autogroup', 'event_calendar') == 'yes')) {
			event_calendar_add_personal_events_from_group($event->guid,$group_guid);
		}
		if (elgg_get_plugin_setting('add_users', 'event_calendar') == 'yes') {
			if (function_exists('autocomplete_member_to_user')) {
				$addusers = get_input('adduser',array());
				foreach($addusers as $adduser) {
					if ($adduser) {
						$user = autocomplete_member_to_user($adduser);
						$user_id = $user->guid;
						event_calendar_add_personal_event($event->guid,$user_id);
						if (elgg_get_plugin_setting('add_users_notify', 'event_calendar') == 'yes') {
							notify_user($user_id, $CONFIG->site->guid, elgg_echo('event_calendar:add_users_notify:subject'),
								sprintf(
									elgg_echo('event_calendar:add_users_notify:body'),
									$user->name,
									$event->title,
									$event->getURL()
								)
							);
						}
					}
				}
			}
		}
	}
	return $event;
}

function event_calendar_get_events_between($start_date,$end_date,$is_count=FALSE,$limit=10,$offset=0,$container_guid=0,$region='-') {	
	$polls_supported = elgg_is_active_plugin('event_poll');
	if ($is_count) {
		$count = event_calendar_get_entities_from_metadata_between2('start_date','end_date',
		$start_date, $end_date, "object", "event_calendar", 0, $container_guid, $limit,$offset,"",0,false,true,$region);
		return $count;
	} else {
		$events = event_calendar_get_entities_from_metadata_between2('start_date','end_date',
			$start_date, $end_date, "object", "event_calendar", 0, $container_guid, $limit,$offset,"",0,false,false,$region);
		$repeating_events = event_calendar_get_repeating_events_between($start_date,$end_date,$container_guid,$region);
		$all_events = event_calendar_merge_repeating_events($events, $repeating_events);
		if ($polls_supported) {
			elgg_load_library('elgg:event_poll');
			$all_events = event_poll_merge_poll_events($all_events,$start_date,$end_date);
		}
		
		return $all_events;
	}
}

function event_calendar_merge_repeating_events($events, $repeating_events) {
	$non_repeating_events = array();
	foreach($events as $e) {
		if ($e->repeats != 'yes') {
			$non_repeating_events[] = array('event' => $e,'data' => array(array('start_time' => $e->start_date, 'end_time' => $e->real_end_time)));
		}
	}
	
	return array_merge($non_repeating_events, $repeating_events);
}

function event_calendar_get_repeating_events_between($start_date,$end_date,$container_guid,$region) {
	// game plan: get all repeating events with start date <= $end_date and then generate all possible events
	// sanity check
	if ($start_date <= $end_date) {
		$options = array(
			'type' => 'object',
			'subtype' => 'event_calendar',
			'limit' => 0,
			'metadata_name_value_pairs' => array(
												array(
													'name' => 'start_date',
	                                         		'value' => $end_date,
	                                         		'operand' => '<='
												),
												array(
													'name' => 'repeats',
													'value' => 'yes'
												),
											)
		);
		if ($container_guid) {
			if (is_array($container_guid)) {
				$options['container_guids'] = $container_guid;
			} else {
				$options['container_guid'] = $container_guid;
			}
		}
		
		if ($region && $region != '-') {
			$options['metadata_name_value_pairs'][] = array(
													'name' => 'region',
													'value' => $region
												);
		}
		
		$events = elgg_get_entities_from_metadata($options);
	}
	return event_calendar_get_repeating_event_structure($events, $start_date, $end_date);
}


function event_calendar_get_repeating_event_structure($events, $start_date, $end_date) {
	$dow = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
	$repeating_events = array();
	if ($events) {
		$incs = array();
		foreach($events as $e) {
			$repeat_data = array();
			$day_num = date('N',$e->start_date)-1;
			for($d=0;$d<7;$d++) {
				$fn = 'event-calendar-repeating-'.$dow[$d].'-value';
				if ($e->$fn) {
					$increment = $d - $day_num;
					$incs[] = $increment;
				}
			}			
			if ($incs) {			
				sort($incs);
	
				$repeat_interval = $e->repeat_interval;
				$event_start_time = $e->start_date;
				$event_end_time = $e->real_end_time;
				$week = 0;
				if ($event_start_time <= $event_end_time) {
					$more_to_do = TRUE;
					$cur_start_time = $event_start_time;
					$cur_end_time = $event_end_time;
					// keep generating events until after $end_date
					// repeat_times is a sanity check to prevent infinite loops in case of bad data
					$repeat_times = 0;
					do {
						foreach($incs as $inc) {
							//$seconds = $inc*60*60*24;
							if ($inc >=0) {
								$tinc = "+ " . $inc;
							} else {
								$tinc = $inc;
							}
							$this_start_time = strtotime($tinc . " day", $cur_start_time);
							$this_end_time = strtotime($tinc . " day", $cur_end_time);
							if ($this_start_time > $end_date) {
								$more_to_do = FALSE;
								break;
							}
							if ($this_start_time >= $event_start_time) {
								$repeat_data[] = array (
									'start_time' => $this_start_time,
									'end_time' => $this_end_time,
								);
							}
						}
						// repeat_interval weeks later
						$week += $repeat_interval;
						$cur_start_time = strtotime("+" . $week . " week", $event_start_time);							
						$cur_end_time = strtotime("+" . $week ." week", $event_end_time);
						$repeat_times += 1;
					} while ($repeat_times < 1000 && $more_to_do);
				}
			}
			$repeating_events[] = array('event'=>$e,'data'=>$repeat_data);
		}
	}
	return $repeating_events;		
}

function event_calendar_get_open_events_between($start_date,$end_date,
$is_count,$limit=10,$offset=0,$container_guid=0,$region='-', $meta_max = 'spots', $annotation_name = 'personal_event') {
	if ($is_count) {
		$count = event_calendar_get_entities_from_metadata_between2('start_date','end_date',
		$start_date, $end_date, "object", "event_calendar", 0, $container_guid, $limit,$offset,"",0,false,true,$region,$meta_max,$annotation_name);
		return $count;
	} else {
		$events = event_calendar_get_entities_from_metadata_between2('start_date','end_date',
		$start_date, $end_date, "object", "event_calendar", 0, $container_guid, $limit,$offset,"",0,false,false,$region,$meta_max,$annotation_name);
		//return event_calendar_vsort($events,'start_date');
		$repeating_events = event_calendar_get_open_repeating_events_between($start_date,$end_date,$container_guid,$region);
		$all_events = event_calendar_merge_repeating_events($events, $repeating_events);
		return $all_events;
	}
}

function event_calendar_get_open_repeating_events_between($start_date,$end_date,$container_guid,$region) {
	$db_prefix = elgg_get_config('dbprefix');
	$meta_max = 'spots';
	$annotation_name = 'personal_event';
	$joins = array();
	$wheres = array();
	$meta_max_n = get_metastring_id($meta_max);
	$ann_n = get_metastring_id($annotation_name);
	if (!$meta_max_n || !$ann_n) {
		if ($count) {
			return 0;
		} else {
			return false;
		}
	}

	$joins[] = "LEFT JOIN {$dbprefix}metadata m4 ON (e.guid = m4.entity_guid AND m4.name_id=$meta_max_n) ";
	$joins[] = "LEFT JOIN {$dbprefix}metastrings ms4 ON (m4.value_id = ms4.id) ";
	$wheres[] = "((ms4.string is null) OR (ms4.string = \"\") OR (CONVERT(ms4.string,SIGNED) > (SELECT count(id) FROM {$dbprefix}annotations ann WHERE ann.entity_guid = e.guid AND name_id = $ann_n GROUP BY entity_guid)))";

	// sanity check
	if ($start_date <= $end_date) {
		$options = array(
			'type' => 'object',
			'subtype' => 'event_calendar',
			'limit' => 0,
			'metadata_name_value_pairs' => array(
												array(
													'name' => 'start_date',
	                                         		'value' => $end_date,
	                                         		'operand' => '<='
												),
												array(
													'name' => 'repeats',
													'value' => 'yes'
												),
											),
			'joins' => $joins,
			'wheres' => $wheres,
				
		);
		if ($container_guid) {
			if (is_array($container_guid)) {
				$options['container_guids'] = $container_guid;
			} else {
				$options['container_guid'] = $container_guid;
			}
		}
		
		if ($region && $region != '-') {
			$options['metadata_name_value_pairs'][] = array(
													'name' => 'region',
													'value' => $region
												);
		}
		
		$events = elgg_get_entities_from_metadata($options);
	}
	return event_calendar_get_repeating_event_structure($events, $start_date, $end_date);
}

function event_calendar_get_events_for_user_between($start_date,$end_date,$is_count,$limit=10,$offset=0,$user_guid,$container_guid=0,$region='-') {
	if ($is_count) {
		// old way
		$count = event_calendar_get_entities_from_metadata_between('start_date','end_date',
		$start_date, $end_date, "object", "event_calendar", $user_guid, $container_guid, $limit,$offset,"",0,true,true,$region);

		return $count;
	} else {
		$events = event_calendar_get_entities_from_metadata_between('start_date','end_date',
		$start_date, $end_date, "object", "event_calendar", $user_guid, $container_guid, $limit,$offset,"",0,true,false,$region);
		//return event_calendar_vsort($events,'start_date');
		return $events;
	}
}

function event_calendar_get_events_for_user_between2($start_date,$end_date,$is_count,$limit=10,$offset=0,$user_guid,$container_guid=0,$region='-') {
	$options_new_way = 	array(
			'type' => 'object',
			'subtype' => 'event_calendar',
			'relationship' => 'personal_event',
			'relationship_guid' => $user_guid,
			'metadata_name_value_pairs' => array(	array(	'name' => 'start_date',
                                         					'value' => $start_date,
                                         					'operand' => '>='),
													array(	'name' => 'real_end_time',
                                         					'value' => $end_date,
                                         					'operand' => '<=')
			),
	);

	if ($container_guid) {
		$options_new_way['container_guid'] = $container_guid;
	}
	if ($region && $region != '-') {
		$options_new_way['metadata_name_value_pairs'][] = array('name'=>'region','value'=>sanitize_string($region));
	}
	if ($is_count) {
		// old way
		$count_old_way = event_calendar_get_entities_from_metadata_between('start_date','real_end_time',
		$start_date, $end_date, "object", "event_calendar", $user_guid, $container_guid, $limit,$offset,"",0,true,true,$region);
		// new way
		$options_new_way['count'] = TRUE;
		$count_new_way = elgg_get_entities_from_relationship($options_new_way);
		return $count_old_way+$count_new_way;
	} else {
		$events_old_way = event_calendar_get_entities_from_metadata_between('start_date','real_end_time',
		$start_date, $end_date, "object", "event_calendar", $user_guid, $container_guid, $limit,$offset,"",0,true,false,$region);
		$options_new_way['limit'] = $limit;
		$options_new_way['offset'] = $offset;
		$options_new_way['order_by_metadata'] = array(array('name'=>'start_date','direction'=>'ASC','as'=>'integer'));
		//print_r($options_new_way);
		$events_new_way = elgg_get_entities_from_relationship($options_new_way);
		//return event_calendar_vsort($events,'start_date');
		$repeating_events = event_calendar_get_repeating_events_for_user_between($user_guid,$start_date,$end_date,$container_guid,$region);
		$all_events = event_calendar_merge_repeating_events(array_merge($events_old_way,$events_new_way), $repeating_events);
		return $all_events;
	}
}

function event_calendar_get_repeating_events_for_user_between($user_guid,$start_date,$end_date,$container_guid,$region) {
	$options = 	array(
			'type' => 'object',
			'subtype' => 'event_calendar',
			'relationship' => 'personal_event',
			'relationship_guid' => $user_guid,
			'metadata_name_value_pairs' => array(
												array(
													'name' => 'start_date',
	                                         		'value' => $end_date,
	                                         		'operand' => '<='
												),
												array(
													'name' => 'repeats',
													'value' => 'yes'
												),
											)
	);
	
	if ($container_guid) {
		if (is_array($container_guid)) {
			$options['container_guids'] = $container_guid;
		} else {
			$options['container_guid'] = $container_guid;
		}
	}
	
	if ($region && $region != '-') {
		$options['metadata_name_value_pairs'][] = array(
												'name' => 'region',
												'value' => $region
											);
	}
	
	$events = elgg_get_entities_from_relationship($options);
	return event_calendar_get_repeating_event_structure($events, $start_date, $end_date);
}

function event_calendar_get_repeating_events_for_friends_between($user_guid,$friend_list,$start_date,$end_date,$container_guid=0,$region='-') {

	$db_prefix = elgg_get_config('dbprefix');
	$options = 	array(
			'type' => 'object',
			'subtype' => 'event_calendar',
			'metadata_name_value_pairs' => array(
				array(	'name' => 'start_date',
                        'value' => $end_date,
                        'operand' => '<='
				),
				array(	'name' => 'repeats',
                        'value' => 'yes'
				)
			),
			'joins' => array("JOIN {$db_prefix}entity_relationships r ON (r.guid_two = e.guid)"),
			'wheres' => array("r.relationship = 'personal_event'","r.guid_one IN ($friend_list)"),
	);
		
 	if ($container_guid) {
		if (is_array($container_guid)) {
			$options['container_guids'] = $container_guid;
		} else {
			$options['container_guid'] = $container_guid;
		}
	}
	if ($region && $region != '-') {
		$options['metadata_name_value_pairs'][] = array('name'=>'region','value'=>sanitize_string($region));
	}
	
	$events = elgg_get_entities_from_relationship($options);
	return event_calendar_get_repeating_event_structure($events, $start_date, $end_date);
}

function event_calendar_get_events_for_friends_between($start_date,$end_date,$is_count,$limit=10,$offset=0,$user_guid,$container_guid=0,$region='-') {
	if ($user_guid) {
		$friends = get_user_friends($user_guid,"",5000);
		if ($friends) {
			$friend_guids = array();
			foreach($friends as $friend) {
				$friend_guids[] = $friend->getGUID();
			}
			$friend_list = implode(",",$friend_guids);
			// elgg_get_entities_from_relationship does not take multiple relationship guids, so need some custom joins and wheres
			$db_prefix = elgg_get_config('dbprefix');
			$options_new_way = 	array(
				'type' => 'object',
				'subtype' => 'event_calendar',
				'metadata_name_value_pairs' => array(array(	'name' => 'start_date',
	                                         				'value' => $start_date,
	                                         				'operand' => '>='),
													array(	'name' => 'real_end_time',
	                                         				'value' => $end_date,
	                                         				'operand' => '<=')
				),
				'joins' => array("JOIN {$db_prefix}entity_relationships r ON (r.guid_two = e.guid)"),
				'wheres' => array("r.relationship = 'personal_event'","r.guid_one IN ($friend_list)"),
			);
				
			if ($container_guid) {
				$options_new_way['container_guid'] = $container_guid;
			}
			if ($region && $region != '-') {
				$options_new_way['metadata_name_value_pairs'][] = array('name'=>'region','value'=>sanitize_string($region));
			}
			if ($is_count) {
				$count_old_way = event_calendar_get_entities_from_metadata_between('start_date','end_date',
				$start_date, $end_date, "object", "event_calendar", $friend_guids, $container_guid, $limit,$offset,"",0,true,true,$region);
				$options_new_way['count'] = TRUE;
				$count_new_way = elgg_get_entities_from_metadata($options_new_way);
				return $count_old_way + $count_new_way;
			} else {
				$events_old_way = event_calendar_get_entities_from_metadata_between('start_date','end_date',
				$start_date, $end_date, "object", "event_calendar", $friend_guids, $container_guid, $limit,$offset,"",0,true,false,$region);
				//return event_calendar_vsort($events,'start_date');
				$options_new_way['limit'] = $limit;
				$options_new_way['offset'] = $offset;
				$options_new_way['order_by_metadata'] = array(array('name'=>'start_date','direction'=>'ASC','as'=>'integer'));
				//print_r($options_new_way);
				$events_new_way = elgg_get_entities_from_metadata($options_new_way);
				$repeating_events = event_calendar_get_repeating_events_for_friends_between($user_guid,$friend_list,$start_date,$end_date,$container_guid,$region);
				$all_events = event_calendar_merge_repeating_events(array_merge($events_old_way,$events_new_way), $repeating_events);
				return $all_events;
			}
		}
	}
	return array();
}

function event_calendar_vsort($original,$field, $descending = false) {
	if (!$original) {
		return $original;
	}
	$sortArr = array();

	foreach ( $original as $key => $item ) {
		$sortArr[ $key ] = $item->$field;
	}

	if ( $descending ) {
		arsort( $sortArr );
	} else {
		asort( $sortArr );
	}

	$resultArr = array();
	foreach ( $sortArr as $key => $value ) {
		$resultArr[ $key ] = $original[ $key ];
	}

	return $resultArr;
}

// TODO - replace with Elgg API if possible

/**
 * Return a list of entities based on the given search criteria.
 * In this case, returns entities with the given metadata between two values inclusive
 *
 * @param mixed $meta_start_name
 * @param mixed $meta_end_name
 * @param mixed $meta_start_value - start of metadata range, must be numerical value
 * @param mixed $meta_end_value - end of metadata range, must be numerical value
 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param mixed $owner_guid Either one integer user guid or an array of user guids
 * @param int $container_guid If supplied, the result is restricted to events associated with a specific container
 * @param int $limit
 * @param int $offset
 * @param string $order_by Optional ordering.
 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
 * @param boolean $filter Filter by events in personal calendar if true
 * @param true|false $count If set to true, returns the total number of entities rather than a list. (Default: false)
 *
 * @return int|array A list of entities, or a count if $count is set to true
 */
function event_calendar_get_entities_from_metadata_between($meta_start_name, $meta_end_name, $meta_start_value, $meta_end_value, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $container_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $filter = false, $count = false, $region='-')
{
	global $CONFIG;

	// This should not be possible, but a sanity check just in case
	if (!is_numeric($meta_start_value) || !is_numeric($meta_end_value)) {
		return FALSE;
	}

	$meta_start_n = get_metastring_id($meta_start_name);
	$meta_end_n = get_metastring_id($meta_end_name);
	if ($region && $region != '-') {
		$region_n = get_metastring_id('region');
		$region_value_n = get_metastring_id($region);
		if (!$region_n || !$region_value_n) {
			if ($count) {
				return 0;
			} else {
				return FALSE;
			}
		}
	}

	$entity_type = sanitise_string($entity_type);
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;
	//if ($order_by == "") $order_by = "e.time_created desc";
	if ($order_by == "") $order_by = "v.string asc";
	$order_by = sanitise_string($order_by);
	$site_guid = (int) $site_guid;
	if ((is_array($owner_guid) && (count($owner_guid)))) {
		foreach($owner_guid as $key => $guid) {
			$owner_guid[$key] = (int) $guid;
		}
	} else {
		$owner_guid = (int) $owner_guid;
	}

	if ((is_array($container_guid) && (count($container_guid)))) {
		foreach($container_guid as $key => $guid) {
			$container_guid[$key] = (int) $guid;
		}
	} else {
		$container_guid = (int) $container_guid;
	}
	if ($site_guid == 0)
	$site_guid = $CONFIG->site_guid;

	//$access = get_access_list();

	$where = array();

	if ($entity_type!="")
	$where[] = "e.type='$entity_type'";
	if ($entity_subtype)
	$where[] = "e.subtype=$entity_subtype";
	$where[] = "m.name_id='$meta_start_n'";
	$where[] = "m2.name_id='$meta_end_n'";
	$where[] = "((v.string >= $meta_start_value AND v.string <= $meta_end_value) OR ( v2.string >= $meta_start_value AND v2.string <= $meta_end_value) OR (v.string <= $meta_start_value AND v2.string >= $meta_start_value) OR ( v2.string <= $meta_end_value AND v2.string >= $meta_end_value))";
	if ($region && $region != '-') {
		$where[] = "m3.name_id='$region_n'";
		$where[] = "m3.value_id='$region_value_n'";
	}
	if ($site_guid > 0)
	$where[] = "e.site_guid = {$site_guid}";
	if ($filter) {
		if (is_array($owner_guid)) {
			$where[] = "ms2.string in (".implode(",",$owner_guid).")";
		} else if ($owner_guid > 0) {
			$where[] = "ms2.string = {$owner_guid}";
		}
			
		$where[] = "ms.string = 'personal_event'";
	} else {
		if (is_array($owner_guid)) {
			$where[] = "e.owner_guid in (".implode(",",$owner_guid).")";
		} else if ($owner_guid > 0) {
			$where[] = "e.owner_guid = {$owner_guid}";
		}
	}

	if (is_array($container_guid)) {
		$where[] = "e.container_guid in (".implode(",",$container_guid).")";
	} else if ($container_guid > 0)
	$where[] = "e.container_guid = {$container_guid}";

	if (!$count) {
		$query = "SELECT distinct e.* ";
	} else {
		$query = "SELECT count(distinct e.guid) as total ";
	}

	$query .= "from {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metadata m2 on e.guid = m2.entity_guid ";
	if ($filter) {
		$query .= "JOIN {$CONFIG->dbprefix}annotations a ON (a.entity_guid = e.guid) ";
		$query .= "JOIN {$CONFIG->dbprefix}metastrings ms ON (a.name_id = ms.id) ";
		$query .= "JOIN {$CONFIG->dbprefix}metastrings ms2 ON (a.value_id = ms2.id) ";
	}
	if ($region && $region != '-') {
		$query .= "JOIN {$CONFIG->dbprefix}metadata m3 ON (e.guid = m3.entity_guid) ";
	}
	$query .= "JOIN {$CONFIG->dbprefix}metastrings v on v.id = m.value_id JOIN {$CONFIG->dbprefix}metastrings v2 on v2.id = m2.value_id where";
	foreach ($where as $w)
	$query .= " $w and ";
	$query .= get_access_sql_suffix("e"); // Add access controls
	$query .= ' and ' . get_access_sql_suffix("m"); // Add access controls
	$query .= ' and ' . get_access_sql_suffix("m2"); // Add access controls

	if (!$count) {
		$query .= " order by $order_by";
		if ($limit) {
			$query .= " limit $offset, $limit"; // Add order and limit
		}
		$entities = get_data($query, "entity_row_to_elggstar");
		if (elgg_get_plugin_setting('add_to_group_calendar', 'event_calendar') == 'yes') {
			if (get_entity($container_guid) instanceOf ElggGroup) {
				$entities = event_calendar_get_entities_from_metadata_between_related($meta_start_name, $meta_end_name,
					$meta_start_value, $meta_end_value, $entity_type,
					$entity_subtype, $owner_guid, $container_guid,
					0, 0, "", 0,
					false, false, '-',$entities);
			}
		}
		return $entities;
	} else {
		if ($row = get_data_row($query))
		return $row->total;
	}
	return false;
}

// adds any related events (has the display_on_group relation)
// that meet the appropriate criteria

function event_calendar_get_entities_from_metadata_between_related($meta_start_name, $meta_end_name,
$meta_start_value, $meta_end_value, $entity_type = "",
$entity_subtype = "", $owner_guid = 0, $container_guid = 0,
$limit = 10, $offset = 0, $order_by = "", $site_guid = 0,
$filter = false, $count = false, $region='-',$main_events) {

	$main_list = array();
	if ($main_events) {
		foreach ($main_events as $event) {
			$main_list[$event->guid] = $event;
		}
	}
	$related_list = array();
	$related_events = elgg_get_entities_from_relationship(array(
		'relationship' => 'display_on_group',
		'relationship_guid' => $container_guid,
		'inverse_relationship' => TRUE,
	));
	if ($related_events) {
		foreach ($related_events as $event) {
			$related_list[$event->guid] = $event;
		}
	}
	// get all the events (across all containers) that meet the criteria
	$all_events = event_calendar_get_entities_from_metadata_between($meta_start_name, $meta_end_name,
	$meta_start_value, $meta_end_value, $entity_type, $entity_subtype, $owner_guid,
	0, $limit, $offset, $order_by, $site_guid, $filter, $count, $region);

	if ($all_events) {
		foreach($all_events as $event) {
			if (array_key_exists($event->guid,$related_list)
			&& !array_key_exists($event->guid,$main_list)) {
				// add to main events
				$main_events[] = $event;
			}
		}
	}
	return event_calendar_vsort($main_events,$meta_start_name);
}

// TODO: try to replace this with new Elgg 1.7 API
/**
 * Return a list of entities based on the given search criteria.
 * In this case, returns entities with the given metadata between two values inclusive
 *
 * @param mixed $meta_start_name
 * @param mixed $meta_end_name
 * @param mixed $meta_start_value - start of metadata range, must be numerical value
 * @param mixed $meta_end_value - end of metadata range, must be numerical value
 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param mixed $owner_guid Either one integer user guid or an array of user guids
 * @param int $container_guid If supplied, the result is restricted to events associated with a specific container
 * @param int $limit
 * @param int $offset
 * @param string $order_by Optional ordering.
 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
 * @param boolean $filter Filter by events in personal calendar if true
 * @param true|false $count If set to true, returns the total number of entities rather than a list. (Default: false)
 * @param string $meta_max metadata name containing maximum annotation count
 * @param string $annotation_name annotation name to count
 *
 * @return int|array A list of entities, or a count if $count is set to true
 * 
 * TODO: see if the new API is robust enough to avoid this custom query
 */
function event_calendar_get_entities_from_metadata_between2
($meta_start_name, $meta_end_name, $meta_start_value, $meta_end_value,
$entity_type = "", $entity_subtype = "", $owner_guid = 0, $container_guid = 0,
$limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $filter = false,
$count = false, $region='-', $meta_max = '', $annotation_name = '')
{
	global $CONFIG;

	// This should not be possible, but a sanity check just in case
	if (!is_numeric($meta_start_value) || !is_numeric($meta_end_value)) {
		return FALSE;
	}

	$meta_start_n = get_metastring_id($meta_start_name);
	$meta_end_n = get_metastring_id($meta_end_name);
	if ($region && $region != '-') {
		$region_n = get_metastring_id('region');
		$region_value_n = get_metastring_id($region);
		if (!$region_n || !$region_value_n) {
			if ($count) {
				return 0;
			} else {
				return false;
			}
		}
	}

	$entity_type = sanitise_string($entity_type);
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;
	//if ($order_by == "") $order_by = "e.time_created desc";
	if ($order_by == "") $order_by = "v.string asc";
	$order_by = sanitise_string($order_by);
	$site_guid = (int) $site_guid;
	if ((is_array($owner_guid) && (count($owner_guid)))) {
		foreach($owner_guid as $key => $guid) {
			$owner_guid[$key] = (int) $guid;
		}
	} else {
		$owner_guid = (int) $owner_guid;
	}

	if ((is_array($container_guid) && (count($container_guid)))) {
		foreach($container_guid as $key => $guid) {
			$container_guid[$key] = (int) $guid;
		}
	} else {
		$container_guid = (int) $container_guid;
	}
	if ($site_guid == 0)
	$site_guid = $CONFIG->site_guid;

	//$access = get_access_list();

	$where = array();

	if ($entity_type!="")
	$where[] = "e.type='$entity_type'";
	if ($entity_subtype)
	$where[] = "e.subtype=$entity_subtype";
	$where[] = "m.name_id='$meta_start_n'";
	$where[] = "m2.name_id='$meta_end_n'";
	$where[] = "((v.string >= $meta_start_value AND v.string <= $meta_end_value) OR ( v2.string >= $meta_start_value AND v2.string <= $meta_end_value) OR (v.string <= $meta_start_value AND v2.string >= $meta_start_value) OR ( v2.string <= $meta_end_value AND v2.string >= $meta_end_value))";
	if ($region && $region != '-') {
		$where[] = "m3.name_id='$region_n'";
		$where[] = "m3.value_id='$region_value_n'";
	}
	if ($site_guid > 0)
	$where[] = "e.site_guid = {$site_guid}";
	if ($filter) {
		if (is_array($owner_guid)) {
			$where[] = "ms2.string in (".implode(",",$owner_guid).")";
		} else if ($owner_guid > 0) {
			$where[] = "ms2.string = {$owner_guid}";
		}
			
		$where[] = "ms.string = 'personal_event'";
	} else {
		if (is_array($owner_guid)) {
			$where[] = "e.owner_guid in (".implode(",",$owner_guid).")";
		} else if ($owner_guid > 0) {
			$where[] = "e.owner_guid = {$owner_guid}";
		}
	}

	if (is_array($container_guid)) {
		$where[] = "e.container_guid in (".implode(",",$container_guid).")";
	} else if ($container_guid > 0)
	$where[] = "e.container_guid = {$container_guid}";

	if (!$count) {
		$query = "SELECT distinct e.* ";
	} else {
		$query = "SELECT count(distinct e.guid) as total ";
	}

	$query .= "FROM {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid JOIN {$CONFIG->dbprefix}metadata m2 on e.guid = m2.entity_guid ";
	if ($filter) {
		$query .= "JOIN {$CONFIG->dbprefix}annotations a ON (a.entity_guid = e.guid) ";
		$query .= "JOIN {$CONFIG->dbprefix}metastrings ms ON (a.name_id = ms.id) ";
		$query .= "JOIN {$CONFIG->dbprefix}metastrings ms2 ON (a.value_id = ms2.id) ";
	}
	if ($region && $region != '-') {
		$query .= "JOIN {$CONFIG->dbprefix}metadata m3 ON (e.guid = m3.entity_guid) ";
	}
	if ($meta_max && $annotation_name) {
		// This groups events for which the meta max name is defined
		// perhaps this should be a left join and accept null values?
		// so it would return groups with no spots defined as well
		$meta_max_n = get_metastring_id($meta_max);
		$ann_n = get_metastring_id($annotation_name);
		if (!$meta_max_n || !$ann_n) {
			if ($count) {
				return 0;
			} else {
				return false;
			}
		}

		$query .= " LEFT JOIN {$CONFIG->dbprefix}metadata m4 ON (e.guid = m4.entity_guid AND m4.name_id=$meta_max_n) ";
		$query .= " LEFT JOIN {$CONFIG->dbprefix}metastrings ms4 ON (m4.value_id = ms4.id) ";
		$where[] = "((ms4.string is null) OR (ms4.string = \"\") OR (CONVERT(ms4.string,SIGNED) > (SELECT count(id) FROM {$CONFIG->dbprefix}annotations ann WHERE ann.entity_guid = e.guid AND name_id = $ann_n GROUP BY entity_guid)))";
	}
	$query .= "JOIN {$CONFIG->dbprefix}metastrings v on v.id = m.value_id JOIN {$CONFIG->dbprefix}metastrings v2 on v2.id = m2.value_id where";
	foreach ($where as $w)
	$query .= " $w AND ";
	$query .= get_access_sql_suffix("e"); // Add access controls
	$query .= ' AND ' . get_access_sql_suffix("m"); // Add access controls
	$query .= ' AND ' . get_access_sql_suffix("m2"); // Add access controls

	if (!$count) {
		$query .= " order by $order_by";
		if ($limit) {
			$query .= " limit $offset, $limit"; // Add order and limit
		}
		$entities = get_data($query, "entity_row_to_elggstar");
		if (elgg_get_plugin_setting('add_to_group_calendar', 'event_calendar') == 'yes') {
			if (get_entity($container_guid) instanceOf ElggGroup) {
				$entities = event_calendar_get_entities_from_metadata_between_related($meta_start_name, $meta_end_name,
					$meta_start_value, $meta_end_value, $entity_type,
					$entity_subtype, $owner_guid, $container_guid,
					0, 0, "", 0,
					false, false, '-',$entities);
			}
		}
		return $entities;
	} else {
		if ($row = get_data_row($query))
		return $row->total;
	}
	return false;
}

function event_calendar_has_personal_event($event_guid,$user_guid) {
	// check legacy implementation and new one
	if (check_entity_relationship($user_guid,'personal_event',$event_guid)) {
		return TRUE;
	} else {
		// use old method for now
		$options = array('guid'=>$event_guid,'annotation_name' => 'personal_event', 'annotation_value' => $user_guid,'count'=>TRUE);
		//$annotations = 	get_annotations($event_guid, "object", "event_calendar", "personal_event", (int) $user_guid, $user_guid);
		if (elgg_get_annotations($options)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

function event_calendar_add_personal_event($event_guid,$user_guid) {
	if ($event_guid && $user_guid) {
		if (!event_calendar_has_personal_event($event_guid,$user_guid)
		&& !event_calendar_has_collision($event_guid,$user_guid)) {
			if (!event_calendar_is_full($event_guid)) {
				add_entity_relationship($user_guid,'personal_event',$event_guid);
				return TRUE;
			}
		}
	}
	return FALSE;
}

function event_calendar_add_personal_events_from_group($event_guid,$group_guid) {
	$members = get_group_members($group_guid, 100000);
	foreach($members as $member) {
		$member_id = $member->getGUID();
		event_calendar_add_personal_event($event_guid,$member_id);
	}
}

function event_calendar_remove_personal_event($event_guid,$user_guid) {
	remove_entity_relationship($user_guid,'personal_event',$event_guid);
	// also use old method for now
	$annotations = 	get_annotations($event_guid, "object", "event_calendar", "personal_event", (int) $user_guid, $user_guid);
	if ($annotations) {
		foreach ($annotations as $annotation) {
			$annotation->delete();
		}
	}
}

function event_calendar_get_personal_events_for_user($user_guid,$limit) {
	$events_old_way = elgg_get_entities_from_annotations(array(
		'type' => 'object',
		'subtype' => 'event_calendar',
		'annotation_names' => 'personal_event',
		'annotation_value' => $user_guid,
		'limit' => 0,
	));

	$events_new_way = elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'subtype' => 'event_calendar',
		'relationship' => 'personal_event',
		'relationship_guid' => $user_guid,
		'limit' => 0,
	));

	$events = array_merge($events_old_way,$events_new_way);

	$final_events = array();
	if ($events) {
		$now = time();
		$one_day = 60*60*24;
		// don't show events that have been over for more than a day
		foreach($events as $event) {
			if (($event->start_date > $now-$one_day) || ($event->end_date && ($event->end_date > $now-$one_day))) {
				$final_events[] = $event;
			}
		}
	}
	$sorted = event_calendar_vsort($final_events,'start_date');
	return array_slice($sorted,0,$limit);
}

// the old way used annotations, and the new Elgg 1.8 way uses relationships
// for now this version attempts to bridge the gap by using both methods for older sites

function event_calendar_get_users_for_event($event_guid,$limit,$offset=0,$is_count=FALSE) {
	$options = array(
		'type' => 'user',
		'relationship' => 'personal_event',
		'relationship_guid' => $event_guid,
		'inverse_relationship' => TRUE,
		'limit' => 0,
	);
	if ($is_count) {
		//$count_old_way = count_annotations($event_guid, "object", "event_calendar", "personal_event");
		$count_old_way = elgg_get_annotations(array(
			'guid'=>$event_guid, 
			'type'=>"object", 
			'subtype'=>"event_calendar", 
			'annotation_name' => "personal_event",
			'count'=>TRUE)
		);
		$options ['count'] = TRUE;
		$count_new_way = elgg_get_entities_from_relationship($options);
		return $count_old_way + $count_new_way;
	} else {
		$users_old_way = array();
		//$annotations = get_annotations($event_id, "object", "event_calendar", "personal_event", "", 0, $limit, $offset);
		$annotations = elgg_get_annotations(array(
			'guid'=>$event_guid, 
			'type'=>"object", 
			'subtype'=>"event_calendar", 
			'annotation_name' => "personal_event",
			'limit' => $limit,
			'offset' => $offset)
		);
		if ($annotations) {
			foreach($annotations as $annotation) {
				if (($user = get_entity($annotation->value)) && ($user instanceOf ElggUser)) {
					$users_old_way[] = $user;
				}
			}
		}
		$users_new_way = elgg_get_entities_from_relationship($options);
		return array_merge($users_old_way,$users_new_way);
	}
}

function event_calendar_security_fields() {
	$ts = time();
	$token = generate_action_token($ts);
	return "__elgg_token=$token&__elgg_ts=$ts";
}

function event_calendar_get_events_for_group($group_guid, $limit = 0) {
	$options = array(
		'type' => 'object',
		'subtype' => 'event_calendar',
		'container_guid' => $group_guid,
		'limit' => $limit,
	);
	return elgg_get_entities($options);
}

function event_calendar_convert_time($time) {
	$event_calendar_time_format = elgg_get_plugin_setting('timeformat','event_calendar');
	if ($event_calendar_time_format == '12') {
		$hour = floor($time/60);
		$minute = sprintf("%02d",$time-60*$hour);
		if ($hour < 12) {
			return "$hour:$minute am";
		} else {
			$hour -= 12;
			return "$hour:$minute pm";
		}
	} else {
		$hour = floor($time/60);
		$minute = sprintf("%02d",$time-60*$hour);
		return "$hour:$minute";
	}
}

function event_calendar_format_time($date,$time1,$time2='') {
	if (is_numeric($time1)) {
		$t = event_calendar_convert_time($time1);
		if (is_numeric($time2)) {
			$t .= " - ".event_calendar_convert_time($time2);
		}
		return "$t, $date";
	} else {
		return $date;
	}
}

function event_calender_get_gmt_from_server_time($server_time) {
	$gmtime = $server_time - (int)substr(date('O'),0,3)*60*60;
}

function event_calendar_activated_for_group($group) {
	$group_calendar = elgg_get_plugin_setting('group_calendar', 'event_calendar');
	$group_default = elgg_get_plugin_setting('group_default', 'event_calendar');
	if ($group && ($group_calendar != 'no')) {
		if ( ($group->event_calendar_enable == 'yes') || ((!$group->event_calendar_enable && (!$group_default || $group_default == 'yes')))) {
			return true;
		}
	}
	return false;
}

function event_calendar_get_region($event) {
	$event_calendar_region_list_handles = elgg_get_plugin_setting('region_list_handles', 'event_calendar');
	$region = trim($event->region);
	if ($event_calendar_region_list_handles == 'yes') {
		$region = elgg_echo('event_calendar:region:'.$region);
	}
	return htmlspecialchars($region);
}

function event_calendar_get_type($event) {
	$event_calendar_type_list_handles = elgg_get_plugin_setting('type_list_handles', 'event_calendar');
	$type = trim($event->event_type);
	if ($type) {
		if ($event_calendar_type_list_handles == 'yes') {
			$type = elgg_echo('event_calendar:type:'.$type);
		}
		return htmlspecialchars($type);
	} else {
		return $type;
	}	
}

function event_calendar_get_formatted_full_items($event) {
	$time_bit = event_calendar_get_formatted_time($event);
	$event_calendar_region_display = elgg_get_plugin_setting('region_display', 'event_calendar');
	$event_calendar_type_display = elgg_get_plugin_setting('type_display', 'event_calendar');
	$event_items = array();
	if ($time_bit) {
		$item = new stdClass();
		$item->title = elgg_echo('event_calendar:when_label');
		$item->value = $time_bit;
		$event_items[] = $item;
	}
	$item = new stdClass();
	$item->title = elgg_echo('event_calendar:venue_label');
	$item->value = htmlspecialchars($event->venue);
	$event_items[] = $item;
	if ($event_calendar_region_display == 'yes') {
		$item = new stdClass();
		$item->title = elgg_echo('event_calendar:region_label');
		$item->value = event_calendar_get_region($event);
		$event_items[] = $item;
	}
	if ($event_calendar_type_display == 'yes') {
		$event_type = event_calendar_get_type($event);
		if ($event_type) {
			$item = new stdClass();
			$item->title = elgg_echo('event_calendar:type_label');
			$item->value = event_calendar_get_type($event);
			$event_items[] = $item;
		}
	}
	$item = new stdClass();
	$item->title = elgg_echo('event_calendar:fees_label');
	$item->value = htmlspecialchars($event->fees);
	$event_items[] = $item;
	$item = new stdClass();
	$item->title = elgg_echo('event_calendar:organiser_label');
	$item->value = htmlspecialchars($event->organiser);
	$event_items[] = $item;
	$item = new stdClass();
	$item->title = elgg_echo('event_calendar:contact_label');
	$item->value = htmlspecialchars($event->contact);
	$event_items[] = $item;

	return $event_items;
}

function event_calendar_get_formatted_time($event) {
	if (!$event->start_date) {
		return '';
	}
	$date_format = 'j M Y';
	$event_calendar_times = elgg_get_plugin_setting('times', 'event_calendar') != 'no';

	$start_date = date($date_format,$event->start_date);
	if ($event->end_date) {
		$end_date = date($date_format,$event->end_date);
	}
	if ((!$event->end_date) || ($end_date == $start_date)) {
		if (!$event->all_day && $event_calendar_times) {
			$start_date = event_calendar_format_time($start_date,$event->start_time,$event->end_time);
		}
		$time_bit = $start_date;
	} else {
		if (!$event->all_day && $event_calendar_times) {
			$start_date = event_calendar_format_time($start_date,$event->start_time);
			$end_date = event_calendar_format_time($end_date,$event->end_time);
		}
		$time_bit = "$start_date - $end_date";
	}
	
	if ($event->repeats == 'yes') {
		$dow = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
		$r = array();
		foreach ($dow as $w) {
			$fn = 'event-calendar-repeating-'.$w.'-value';
			if ($event->$fn) {
				$r[] = elgg_echo('event_calendar:dow:full:'.$w);
			}
		}
		$week_bit = implode(", ",$r);
		if ($event->repeat_interval > 1) {
			$week_bit .= ' '.elgg_echo('event_calendar:repeated_event:week_interval',array($event->repeat_interval));
		} else {
			$week_bit .= ' '.elgg_echo('event_calendar:repeated_event:week_single');
		}
		$time_bit = elgg_echo('event_calendar:repeated_event:format',array($time_bit, $week_bit));
	}

	return $time_bit;
}

function event_calendar_get_formatted_date($ts) {
	// TODO: make the date format configurable
	return date('j/n/Y',$ts);
}

function event_calendar_is_full($event_id) {
	$event_calendar_spots_display = elgg_get_plugin_setting('spots_display', 'event_calendar');
	if ($event_calendar_spots_display == 'yes') {
		$count = event_calendar_get_users_for_event($event_id,0,0,TRUE);
		$event = get_entity($event_id);
		if ($event) {
			$spots = $event->spots;
			if (is_numeric($spots)) {
				if ($count >= $spots) {
					return TRUE;
				}
			}
		}
	}
	return FALSE;
}

function event_calendar_has_collision($event_id, $user_id) {
	$no_collisions = elgg_get_plugin_setting('no_collisions', 'event_calendar');
	if ($no_collisions == 'yes') {
		$event = get_entity($event_id);
		if ($event) {
			$start_time = $event->start_date;
			$end_time = event_calendar_get_end_time($event);
			// look to see if the user already has events within this period
			$count = event_calendar_get_events_for_user_between2($start_time,$end_time,TRUE,10,0,$user_id);
			if ($count > 0) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	return FALSE;
}

// this complicated bit of code determines the event end time
function event_calendar_get_end_time($event) {
	$default_length = elgg_get_plugin_setting('collision_length', 'event_calendar');
	$start_time = $event->start_date;
	$end_time = $event->end_time;
	$end_date = $event->end_date;
	if($end_date) {
		if ($end_time) {
			$end_time = $end_date+$end_time*60;
		} else if ($start_time == $end_date) {
			if (is_numeric($default_length)) {
				$end_time = $end_date + $default_length;
			} else {
				// default to an hour length
				$end_time = $start_time + 3600;
			}
		} else {
			$end_time = $end_date;
		}
	} else {
		if ($end_time) {
			if ($event->start_time) {
				$end_time = $start_time + ($end_time*60 - $event->start_time*60);
			} else {
				$end_time = $start_time + $end_time*60;
			}
		} else {
			if (is_numeric($default_length)) {
				$end_time = $start_time + $default_length;
			} else {
				// default to an hour length
				$end_time = $start_time + 3600;
			}
		}
	}

	return $end_time;
}

// a version to allow for some customised options
function event_calendar_view_entity_list($entities, $count, $offset, $limit, $fullview = true, $viewtypetoggle = true, $pagination = true) {
	$count = (int) $count;
	$limit = (int) $limit;

	// do not require views to explicitly pass in the offset
	if (!$offset = (int) $offset) {
		$offset = sanitise_int(get_input('offset', 0));
	}

	$context = elgg_get_context();

	$html = elgg_view('event_calendar/entities/entity_list',array(
		'entities' => $entities,
		'count' => $count,
		'offset' => $offset,
		'limit' => $limit,
		'baseurl' => $_SERVER['REQUEST_URI'],
		'fullview' => $fullview,
		'context' => $context,
		'viewtypetoggle' => $viewtypetoggle,
		'viewtype' => get_input('search_viewtype','list'),
		'pagination' => $pagination
	));

	return $html;
}

// returns open, closed or private for the given event and user
function event_calendar_personal_can_manage($event,$user_id) {
	$status = 'private';
	$event_calendar_personal_manage = elgg_get_plugin_setting('personal_manage', 'event_calendar');
	if (!$event_calendar_personal_manage 
		|| $event_calendar_personal_manage == 'open' 
		|| $event_calendar_personal_manage == 'yes'
		|| (($event_calendar_personal_manage == 'by_event' && (!$event->personal_manage || ($event->personal_manage == 'open'))))) {
		$status = 'open';
	} else {
		// in this case only admins or event owners can manage events on their personal calendars
		if(elgg_is_admin_logged_in()) {
			$status = 'open';
		} else if ($event && ($event->owner_guid == $user_id)) {
			$status = 'open';
		} else if (($event_calendar_personal_manage == 'closed') 
			|| ($event_calendar_personal_manage == 'no')
			|| (($event_calendar_personal_manage == 'by_event') && ($event->personal_manage == 'closed'))) {
			$status = 'closed';
		}
	}

	return $status;
}

function event_calendar_send_event_request($event,$user_guid) {
	$result = FALSE;
	if(add_entity_relationship($user_guid, 'event_calendar_request', $event->guid)) {
		$subject = elgg_echo('event_calendar:request_subject');
		$name = get_entity($user_guid)->name;
		$title = $event->title;
		$url = $event->getUrl();
		$link = elgg_get_site_url().'event_calendar/review_requests/'.$event->guid;
		$message = sprintf(elgg_echo('event_calendar:request_message'),$name,$title,$url,$link);
		notify_user($event->owner_guid,elgg_get_site_entity()->guid,$subject,$message);
		$result = TRUE;
	}
	return $result;
}

// pages

function event_calendar_get_page_content_list($page_type,$container_guid,$start_date,$display_mode,$filter,$region='-') {
	elgg_load_js('elgg.event_calendar');
	global $autofeed;
	$autofeed = true;
	if ($page_type == 'group') {
		if (!event_calendar_activated_for_group($container_guid)) {
			forward();
		}
		elgg_push_breadcrumb(elgg_echo('event_calendar:group_breadcrumb'));
		elgg_push_context('groups');
		elgg_set_page_owner_guid($container_guid);
		$user_guid = elgg_get_logged_in_user_guid();
		if(event_calendar_can_add($container_guid)) {
			elgg_register_menu_item('title', array(
				'name' => 'add',
				'href' => "event_calendar/add/".$container_guid,
				'text' => elgg_echo('event_calendar:add'),
				'class' => 'elgg-button elgg-button-action event-calendar-button-add',
			));
		}
	} else {
		elgg_push_breadcrumb(elgg_echo('item:object:event_calendar'));
		$user_guid = elgg_get_logged_in_user_guid();
		if(event_calendar_can_add($container_guid)) {
			elgg_register_menu_item('title', array(
				'name' => 'add',
				'href' => "event_calendar/add",
				'text' => elgg_echo('event_calendar:add'),
				'class' => 'elgg-button elgg-button-action event-calendar-button-add',
			));
		}
	}	

	$params = event_calendar_generate_listing_params($page_type,$container_guid,$start_date,$display_mode,$filter,$region);
	
	$url = full_url();
	if (substr_count($url, '?')) {
		$url .= "&view=ical";
	} else {
		$url .= "?view=ical";
	}
	
	$url = elgg_format_url($url);
	$menu_options = array(
		'name' => 'ical',
		'id' => 'event-calendar-ical-link',
		'text' => '<img src="'.elgg_get_site_url().'mod/event_calendar/images/ics.png" />',
		'href' => $url,
		'title' => elgg_echo('feed:ical'),
		'priority' => 800,
	);
	$menu_item = ElggMenuItem::factory($menu_options);
	elgg_register_menu_item('extras', $menu_item);

	$body = elgg_view_layout("content", $params);

	return elgg_view_page($title,$body);
}

function event_calendar_get_page_content_edit($page_type,$guid,$start_date='') {
	elgg_load_js('elgg.event_calendar');
	$vars = array();
	$vars['id'] = 'event-calendar-edit';
	$vars['name'] = 'event_calendar_edit';
	// just in case a feature adds an image upload
	$vars['enctype'] = 'multipart/form-data';

	$body_vars = array();

	if ($page_type == 'edit') {
		$title = elgg_echo('event_calendar:manage_event_title');
		$event = get_entity((int)$guid);
		if (elgg_instanceof($event, 'object', 'event_calendar') && $event->canEdit()) {
			$body_vars['event'] = $event;
			$body_vars['form_data'] =  event_calendar_prepare_edit_form_vars($event,$page_type);
			
			$event_container = get_entity($event->container_guid);
			if (elgg_instanceof($event_container, 'group')) {
				elgg_push_breadcrumb(elgg_echo('event_calendar:group_breadcrumb'), 'event_calendar/group/'.$event->container_guid);
				$body_vars['group_guid'] = $event_container->guid;
			} else {
				elgg_push_breadcrumb(elgg_echo('event_calendar:show_events_title'),'event_calendar/list');
				$body_vars['group_guid'] = 0;
			}
			elgg_push_breadcrumb($event->title,$event->getURL());
			elgg_push_breadcrumb(elgg_echo('event_calendar:manage_event_title'));

			$content = elgg_view_form('event_calendar/edit', $vars,$body_vars);
		} else {
			$content = elgg_echo('event_calendar:error_event_edit');
		}
	} else {
		$title = elgg_echo('event_calendar:add_event_title');
		
		if ($guid) {
			// add to group
			$group = get_entity($guid);
			if (elgg_instanceof($group, 'group')) {
				$body_vars['group_guid'] = $guid;
				elgg_push_breadcrumb(elgg_echo('event_calendar:group_breadcrumb'), 'event_calendar/group/'.$guid);
				elgg_push_breadcrumb(elgg_echo('event_calendar:add_event_title'));
				$body_vars['form_data'] = event_calendar_prepare_edit_form_vars(NULL,$page_type,$start_date);
				$content = elgg_view_form('event_calendar/edit', $vars, $body_vars);
			} else {
				$content = elgg_echo('event_calendar:no_group');
			}
		} else {
			$body_vars['group_guid'] = 0;
			elgg_push_breadcrumb(elgg_echo('event_calendar:show_events_title'),'event_calendar/list');

			elgg_push_breadcrumb(elgg_echo('event_calendar:add_event_title'));
			$body_vars['form_data'] = event_calendar_prepare_edit_form_vars(NULL,$page_type,$start_date);

			$content = elgg_view_form('event_calendar/edit', $vars, $body_vars);
		}
	}

	$params = array('title' => $title, 'content' => $content,'filter' => '');

	$body = elgg_view_layout("content", $params);

	return elgg_view_page($title,$body);
}

/**
 * Pull together variables for the edit form
 *
 * @param ElggObject       $event
 * @return array
 */
function event_calendar_prepare_edit_form_vars($event = NULL, $page_type = '', $start_date = '') {

	// input names => defaults
	$now = time();
	$iso_date = date('Y-m-d',$now);
	$now_midnight = strtotime($iso_date);
	if ($start_date) {
		$start_date = strtotime($start_date);
	} else {
		$start_date = $now+60*60;
	}
	$start_time = floor(($now-$now_midnight)/60) + 60;
	$start_time = floor($start_time/5)*5;
	$values = array(
		'title' => NULL,
		'description' => NULL,
		'venue' => NULL,
		'start_date' => $start_date,
		'end_date' => $start_date+60*60,
		'start_time' => $start_time,
		'end_time' => $start_time + 60,
		'spots' => NULL,
		'region' => '-',
		'event_type' => '-',
		'fees' => NULL,
		'contact' => NULL,
		'organiser' => NULL,
		'tags' => NULL,
		'send_reminder' => NULL,
		'reminder_number' => 1,
		'reminder_interval' => 60,
		'repeats' => NULL,
		'repeat_interval' => 1,
		'event-calendar-repeating-monday-value' => 0,
		'event-calendar-repeating-tuesday-value' => 0,
		'event-calendar-repeating-wednesday-value' => 0,
		'event-calendar-repeating-thursday-value' => 0,
		'event-calendar-repeating-friday-value' => 0,
		'event-calendar-repeating-saturday-value' => 0,
		'event-calendar-repeating-sunday-value' => 0,
		'personal_manage' => 'open',
		'web_conference' => NULL,
		'long_description' => NULL,
		'access_id' => ACCESS_DEFAULT,
		'group_guid' => NULL,
	);
	
	if ($page_type == 'schedule') {
		$values['schedule_type'] = 'poll';
	} else {
		$values['schedule_type'] = 'fixed';
	}

	if ($event) {
		foreach (array_keys($values) as $field) {
			if (isset($event->$field)) {
				$values[$field] = $event->$field;
			}
		}
	}

	if (elgg_is_sticky_form('event_calendar')) {
		$sticky_values = elgg_get_sticky_values('event_calendar');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('event_calendar');

	return $values;
}

function event_calendar_generate_listing_params($page_type,$container_guid,$original_start_date,$display_mode,$filter,$region='-') {
	$event_calendar_listing_format = elgg_get_plugin_setting('listing_format', 'event_calendar');
	$event_calendar_spots_display = trim(elgg_get_plugin_setting('spots_display', 'event_calendar'));
	$event_calendar_first_date = trim(elgg_get_plugin_setting('first_date', 'event_calendar'));
	$event_calendar_last_date = trim(elgg_get_plugin_setting('last_date', 'event_calendar'));

	if (!$original_start_date) {
		$original_start_date = date('Y-m-d');
	}
	if ( $event_calendar_first_date && ($original_start_date < $event_calendar_first_date) ) {
		$original_start_date = $event_calendar_first_date;
	}
	if ( $event_calendar_last_date && ($original_start_date > $event_calendar_last_date) ) {
		$original_start_date = $event_calendar_first_date;
	}

	if ($event_calendar_listing_format == 'paged') {
		$start_ts = strtotime($original_start_date);
		$start_date = $original_start_date;
		if ($event_calendar_last_date) {
			$end_ts = strtotime($event_calendar_last_date);
		} else {
			// set to a large number
			$end_ts = 2000000000;
		}
		$mode = 'paged';
	} else {

		// the default interval is one month
		$day = 60*60*24;
		$week = 7*$day;
		$month = 31*$day;

		$mode = trim($display_mode);
		if (!$mode) {
			$mode = 'month';
		}

		if ($mode == "day") {
			$start_date = $original_start_date;
			$end_date = $start_date;
			$start_ts = strtotime($start_date);
			$end_ts = strtotime($end_date)+$day-1;
		} else if ($mode == "week") {
			// need to adjust start_date to be the beginning of the week
			$start_ts = strtotime($original_start_date);
			$start_ts -= date("w",$start_ts)*$day;
			$end_ts = $start_ts + 6*$day;
				
			$start_date = date('Y-m-d',$start_ts);
			$end_date = date('Y-m-d',$end_ts);
		} else {
			$start_ts = strtotime($original_start_date);
			$month = date('m',$start_ts);
			$year = date('Y',$start_ts);
			$start_date = $year.'-'.$month.'-1';
			$end_date = $year.'-'.$month.'-'.getLastDayOfMonth($month,$year);
		}

		if ($event_calendar_first_date && ($start_date < $event_calendar_first_date)) {
			$start_date = $event_calendar_first_date;
		}

		if ($event_calendar_last_date && ($end_date > $event_calendar_last_date)) {
			$end_date = $event_calendar_last_date;
		}

		$start_ts = strtotime($start_date);

		if ($mode == "day") {
			$end_ts = strtotime($end_date)+$day-1;
			$subtitle = elgg_echo('event_calendar:day_label').': '.date('j F Y',strtotime($start_date));
		} else if ($mode == "week") {
			// KJ - fix for end date bug
			//$end_ts = $start_ts + 6*$day;
			$end_ts = $start_ts + 7*$day;
			$subtitle = elgg_echo('event_calendar:week_label').': '.date('j F',$start_ts) . ' - '.date('j F Y',$end_ts);
		} else {
			// KJ - fix for end date bug
			//$end_ts = strtotime($end_date);
			$end_ts = strtotime($end_date)+24*60*60-1;
			$subtitle = date('F Y',$start_ts);
		}
	}
	
	$current_user_guid = elgg_get_logged_in_user_guid();
	
	$access_status = elgg_get_ignore_access();
	
	if ($page_type == 'owner') {
		$container = get_entity($container_guid);
		if (elgg_instanceof($container, 'user')) {
			$auth_token = get_input('auth_token');
			if ($auth_token) {
				$secret_key = event_calendar_get_secret_key();
				if ($secret_key && ($auth_token === sha1($container->username . $secret_key))) {
					elgg_set_ignore_access(TRUE);
				}
			}
			if ($container->canEdit()) {
				$user_guid = $container_guid;
				$group_guid = 0;
			} else {
				register_error('event_calendar:owner:permissions_error');
				forward();
				exit;
			}
		} else {
			register_error('event_calendar:owner:permissions_error');
			forward();
			exit;
		}		
	} else {
		$user_guid = $current_user_guid;
		$group_guid = $container_guid;
	}

	$offset = get_input('offset');
	$limit = get_input('limit',15);

	if ($event_calendar_spots_display == 'yes') {
		if (!$filter) {
			$filter = 'open';
		}
	} else {
		if (!$filter) {
			$filter = 'all';
		}
	}
	
	if ($filter == 'all') {
		$count = event_calendar_get_events_between($start_ts,$end_ts,true,$limit,$offset,$container_guid,$region);
		$events = event_calendar_get_events_between($start_ts,$end_ts,false,$limit,$offset,$container_guid,$region);
	} else if ($filter == 'open') {
		$count = event_calendar_get_open_events_between($start_ts,$end_ts,true,$limit,$offset,$container_guid,$region);
		$events = event_calendar_get_open_events_between($start_ts,$end_ts,false,$limit,$offset,$container_guid,$region);
	} else if ($filter == 'friends') {
		$count = event_calendar_get_events_for_friends_between($start_ts,$end_ts,true,$limit,$offset,$user_guid,$container_guid,$region);
		$events = event_calendar_get_events_for_friends_between($start_ts,$end_ts,false,$limit,$offset,$user_guid,$container_guid,$region);
	} else if ($filter == 'mine') {
		$count = event_calendar_get_events_for_user_between2($start_ts,$end_ts,true,$limit,$offset,$user_guid,$container_guid,$region);
		$events = event_calendar_get_events_for_user_between2($start_ts,$end_ts,false,$limit,$offset,$user_guid,$container_guid,$region);
	}

	$vars = array(
				'original_start_date' => $original_start_date,
				'start_date'	=> $start_date,
				'end_date'		=> $end_date,
				'first_date'	=> $event_calendar_first_date,
				'last_date'		=> $event_calendar_last_date,
				'mode'			=> $mode,
				'events'		=> $events,
				'count'			=> $count,
				'offset'		=> $offset,
				'limit'			=> $limit,
				'group_guid'	=> $group_guid,
				'filter'		=> $filter,
				'region'		=> $region,
				'listing_format' => $event_calendar_listing_format,
	);

	$content = elgg_view('event_calendar/show_events', $vars);
	if ($page_type == 'owner') {
		$filter_override = '';
	} else {
		$filter_override = elgg_view('event_calendar/filter_menu',$vars);
	}

	if ($event_calendar_listing_format == 'paged') {
		$title = elgg_echo('event_calendar:upcoming_events_title');
	} else if ($event_calendar_listing_format == 'full') {
		$title = elgg_echo('event_calendar:show_events_title');
	} else if ($page_type == 'group') {
		$title = elgg_echo('event_calendar:group'). ' ('.$subtitle.')';
	} else {
		$title = elgg_echo('event_calendar:listing_title:'.$filter). ' ('.$subtitle.')';
	}

	$params = array('title' => $title, 'content' => $content, 'filter_override'=>$filter_override);
	elgg_set_ignore_access($access_status);
	return $params;
}

function event_calendar_get_page_content_view($event_guid,$light_box = FALSE) {
	// add personal calendar button and links
	elgg_push_context('event_calendar:view');
	$event = get_entity($event_guid);

	if (!elgg_instanceof($event, 'object', 'event_calendar')) {
		$content = elgg_echo('event_calendar:error_nosuchevent');
		$title = elgg_echo('event_calendar:generic_error_title');
	} else {
		$title = htmlspecialchars($event->title);
		$event_container = get_entity($event->container_guid);
		if (elgg_instanceof($event_container, 'group')) {
			if ($event_container->canEdit()) {
				event_calendar_handle_menu($event_guid);
			}
			elgg_push_breadcrumb(elgg_echo('event_calendar:group_breadcrumb'), 'event_calendar/group/'.$event->container_guid);
		} else {
			if ($event->canEdit()) {
				event_calendar_handle_menu($event_guid);
			}
			elgg_push_breadcrumb(elgg_echo('event_calendar:show_events_title'),'event_calendar/list');
		}

		elgg_push_breadcrumb($event->title);
		$content = elgg_view_entity($event, array('full_view' => true,'light_box'=>$light_box));
		//check to see if comment are on - TODO - add this feature to all events
		if ($event->comments_on != 'Off') {
			$content .= elgg_view_comments($event);
		}
	}
	
	if ($light_box) {
		return '<div class="event-calendar-lightbox">'.elgg_view_title($title).$content.'</div>';
	} else {
		$params = array('title' => $title, 'content' => $content,'filter' => '');
	
		$body = elgg_view_layout("content", $params);
	
		return elgg_view_page($title,$body);
	}
}

function event_calendar_get_page_content_display_users($event_guid) {
	elgg_load_js('elgg.event_calendar');
	$event = get_entity($event_guid);

	if (!elgg_instanceof($event, 'object', 'event_calendar')) {
		$content = elgg_echo('event_calendar:error_nosuchevent');
		$title = elgg_echo('event_calendar:generic_error_title');
	} else {
		event_calendar_handle_menu($event_guid);
		$title = elgg_echo('event_calendar:users_for_event_title',array(htmlspecialchars($event->title)));
		$event_container = get_entity($event->container_guid);
		if (elgg_instanceof($event_container, 'group')) {
			elgg_push_context('groups');
			elgg_set_page_owner_guid($event->container_guid);
			elgg_push_breadcrumb(elgg_echo('event_calendar:group_breadcrumb'), 'event_calendar/group/'.$event->container_guid);
		} else {
			elgg_push_breadcrumb(elgg_echo('event_calendar:show_events_title'),'event_calendar/list');
		}
		elgg_push_breadcrumb($event->title,$event->getURL());
		elgg_push_breadcrumb(elgg_echo('event_calendar:users_for_event_breadcrumb'));
		$limit = 12;
		$offset = get_input('offset', 0);
		$users = event_calendar_get_users_for_event($event_guid,$limit,$offset,false);
		$options = array(
			'full_view' => FALSE,
			'list_type_toggle' => FALSE,
			'limit'=>$limit,
			'event_calendar_event'=>$event,
		);
		elgg_extend_view('user/default','event_calendar/calendar_toggle');
		$content = elgg_view_entity_list($users,$options);
	}
	$params = array('title' => $title, 'content' => $content,'filter' => '');

	$body = elgg_view_layout("content", $params);

	return elgg_view_page($title,$body);
}

// display a list of all the members of the container of $event_guid and allowing
// adding or removing them

function event_calendar_get_page_content_manage_users($event_guid) {
	// TODO: make this an optional feature, toggled off
	elgg_load_js('elgg.event_calendar');
	$event = get_entity($event_guid);
	$limit = 10;
	$offset = get_input('offset', 0);
	
	$event_calendar_add_users = elgg_get_plugin_setting('add_users', 'event_calendar');
	if ($event_calendar_add_users != 'yes') {
		register_error(elgg_echo('event_calendar:feature_not_activated'));
		forward();
		exit;
	}

	if (!elgg_instanceof($event, 'object', 'event_calendar')) {
		$content = elgg_echo('event_calendar:error_nosuchevent');
		$title = elgg_echo('event_calendar:generic_error_title');
	} else {
		event_calendar_handle_menu($event_guid);
		$title = elgg_echo('event_calendar:manage_users:title',array($event->title));
		$event_container = get_entity($event->container_guid);
		if ($event_container->canEdit()) {
			if (elgg_instanceof($event_container, 'group')) {
				elgg_push_context('groups');
				elgg_set_page_owner_guid($event->container_guid);
				elgg_push_breadcrumb(elgg_echo('event_calendar:group_breadcrumb'), 'event_calendar/group/'.$event->container_guid);
				elgg_register_menu_item('title', array(
					'name' => 'remove_from_group_members',
					'href' => elgg_add_action_tokens_to_url('action/event_calendar/remove_from_group_members?event_guid='.$event_guid),
					'text' => elgg_echo('event_calendar:remove_from_group_members:button'),
					'class' => 'elgg-button elgg-button-action',
				));
				elgg_register_menu_item('title', array(
					'name' => 'add_to_group_members',
					'href' => elgg_add_action_tokens_to_url('action/event_calendar/add_to_group_members?event_guid='.$event_guid),
					'text' => elgg_echo('event_calendar:add_to_group_members:button'),
					'class' => 'elgg-button elgg-button-action',
				));
				$users = $event_container->getMembers($limit,$offset);
				$count = $event_container->getMembers($limit,$offset,TRUE);
				elgg_extend_view('user/default','event_calendar/calendar_toggle');
				$options = array(
					'full_view' => FALSE,
					'list_type_toggle' => FALSE,
					'limit'=>$limit,
					'event_calendar_event'=>$event,
					'pagination' => TRUE,
					'count'=>$count,
				);				
				$content .= elgg_view_entity_list($users,$options,$offset,$limit);
			} else {
				elgg_push_breadcrumb(elgg_echo('event_calendar:show_events_title'),'event_calendar/list');
				$content = '<p>'.elgg_echo('event_calendar:manage_users:description').'</p>';
				$content .= elgg_view_form('event_calendar/manage_subscribers',array(),array('event'=>$event));	
			}
			elgg_push_breadcrumb($event->title,$event->getURL());
			elgg_push_breadcrumb(elgg_echo('event_calendar:manage_users:breadcrumb'));
			
		} else {
			$content = elgg_echo('event_calendar:manage_users:unauthorized');
		}
	}
	$params = array('title' => $title, 'content' => $content,'filter' => '');

	$body = elgg_view_layout("content", $params);

	return elgg_view_page($title,$body);
}

function event_calendar_get_page_content_review_requests($event_guid) {
	$event = get_entity($event_guid);

	if (!elgg_instanceof($event, 'object', 'event_calendar')) {
		$content = elgg_echo('event_calendar:error_nosuchevent');
		$title = elgg_echo('event_calendar:generic_error_title');
	} else {
		event_calendar_handle_menu($event_guid);
		$title = elgg_echo('event_calendar:review_requests_title',array(htmlspecialchars($event->title)));
		$event_container = get_entity($event->container_guid);
		if (elgg_instanceof($event_container, 'group')) {
			elgg_push_context('groups');
			elgg_set_page_owner_guid($event->container_guid);
			elgg_push_breadcrumb(elgg_echo('event_calendar:group_breadcrumb'), 'event_calendar/group/'.$event->container_guid);
		} else {
			elgg_push_breadcrumb(elgg_echo('event_calendar:show_events_title'),'event_calendar/list');
		}
		elgg_push_breadcrumb($event->title,$event->getURL());
		elgg_push_breadcrumb(elgg_echo('event_calendar:review_requests_menu_title'));

		if ($event->canEdit()) {
			$requests = elgg_get_entities_from_relationship(
			array(
					'relationship' => 'event_calendar_request', 
					'relationship_guid' => $event_guid, 
					'inverse_relationship' => TRUE, 
					'limit' => 0)
			);
			if ($requests) {
				$content = elgg_view('event_calendar/review_requests',array('requests' => $requests, 'entity' => $event));
			} else {
				$content = elgg_echo('event_calendar:review_requests_request_none');
			}
		} else {
			$content = elgg_echo('event_calendar:review_requests_error');
		}
	}
	$params = array('title' => $title, 'content' => $content,'filter' => '');

	$body = elgg_view_layout("content", $params);

	return elgg_view_page($title,$body);
}

function event_calendar_handle_menu($event_guid) {
	$event = get_entity($event_guid);
	$event_calendar_personal_manage = elgg_get_plugin_setting('personal_manage', 'event_calendar');
	if ((($event_calendar_personal_manage == 'by_event') && ($event->personal_manage == 'closed')) 
		|| (($event_calendar_personal_manage == 'closed') || ($event_calendar_personal_manage == 'no'))) {
		$url =  "event_calendar/review_requests/$event_guid";
		$item = new ElggMenuItem('event-calendar-0review_requests', elgg_echo('event_calendar:review_requests_menu_title'), $url);
		$item->setSection('event_calendar');
		elgg_register_menu_item('page', $item);
		//add_submenu_item(elgg_echo('event_calendar:review_requests_title'), $CONFIG->wwwroot . "pg/event_calendar/review_requests/".$event_id, '0eventcalendaradmin');
	}
	$event_calendar_add_users = elgg_get_plugin_setting('add_users', 'event_calendar');
	if ($event_calendar_add_users == 'yes') {
		$url =  "event_calendar/manage_users/$event_guid";
		$item = new ElggMenuItem('event-calendar-1manage_users', elgg_echo('event_calendar:manage_users:breadcrumb'), $url);
		$item->setSection('event_calendar');
		elgg_register_menu_item('page', $item);
	}
}
function event_calendar_get_secret_key() {
	$key_file_name = elgg_get_plugin_setting('ical_auth_file_name','event_calendar');
	if ($key_file_name && file_exists($key_file_name)) {	
		$key = (require($key_file_name));
		
		return $key['tokenSecretKey'];
	} else {
		return FALSE;
	}
}

function getLastDayOfMonth($month,$year) {
	return idate('d', mktime(0, 0, 0, ($month + 1), 0, $year));
}

function event_calendar_modify_full_calendar($event_guid,$day_delta,$minute_delta,$start_time,$resend,$minutes,$iso_date) {
	$event = get_entity($event_guid);
	if (elgg_instanceof($event,'object','event_calendar') && $event->canEdit()) {
		if ($event->is_event_poll) {
			if (elgg_is_active_plugin('event_poll')) {
				elgg_load_library('elgg:event_poll');
				return event_poll_change($event_guid,$day_delta,$minute_delta,$start_time,$resend,$minutes,$iso_date);
			} else {
				return FALSE;
			}
		} else {
			$event->start_date = strtotime("$day_delta days",$event->start_date)+60*$minute_delta;
			if ($event->end_date) {
				$event->end_date = strtotime("$day_delta days",$event->end_date);
			}
			$times = elgg_get_plugin_setting('times','event_calendar');
			//$inc = 24*60*60*$day_delta+60*$minute_delta;
			
			//$event->real_end_time += $inc;
			$event->real_end_time = strtotime("$day_delta days",$event->real_end_time)+60*$minute_delta;
			if ($times != 'no') {
				$event->start_time += $minute_delta;
				if ($event->end_time) {
					$event->end_time += $minute_delta;
				}
			}
			return TRUE;
		}
	}
	return FALSE;
}

function event_calendar_get_page_content_fullcalendar_events($start_date,$end_date,$filter='all',$container_guid=0,$region='-') {
	//print "$start_date - $end_date";
	$start_ts = strtotime($start_date);
	$end_ts = strtotime($end_date);
	if ($filter == 'all') {
		$events = event_calendar_get_events_between($start_ts,$end_ts,false,0,0,$container_guid,$region);
	} else if ($filter == 'open') {
		$events = event_calendar_get_open_events_between($start_ts,$end_ts,false,0,0,$container_guid,$region);
	} else if ($filter == 'friends') {
		$user_guid = elgg_get_logged_in_user_guid();
		$events = event_calendar_get_events_for_friends_between($start_ts,$end_ts,false,0,0,$user_guid,$container_guid,$region);
	} else if ($filter == 'mine') {
		$user_guid = elgg_get_logged_in_user_guid();
		$events = event_calendar_get_events_for_user_between2($start_ts,$end_ts,false,0,0,$user_guid,$container_guid,$region);
	}
	$event_array = array();
	$times_supported = elgg_get_plugin_setting('times','event_calendar') != 'no';
	$polls_supported = elgg_is_active_plugin('event_poll');
	foreach($events as $e) {
		$event = $e['event'];
		$event_data = $e['data'];
		$c = count($event_data);
		foreach($event_data as $ed) {
			$event_item = array(
				'guid' => $event->guid,
				'title' => $event->title,
				'start' => date('c',$ed['start_time']),
				'end' => date('c',$ed['end_time']),
			);
			if (!$times_supported || ($event->schedule_type == 'all_day')) {
				$event_item['allDay'] = TRUE;
			} else {
				$event_item['allDay'] = FALSE;
			}
			
			if ($polls_supported && isset($e['is_event_poll']) && $e['is_event_poll']) {
				$event_item['className'] = 'event-poll-class';
				$event_item['title'] .= ' '.elgg_echo('event_calendar:poll_suffix');
				$event_item['is_event_poll'] = TRUE;
				$event_item['url'] = elgg_get_site_url().'event_poll/vote/'.$event->guid;
				$event_item['minutes'] = $ed['minutes'];
				$event_item['iso_date'] = $ed['iso_date'];
			} else {
				$event_item['id'] = $event->guid;
				$event_item['is_event_poll'] = FALSE;
				$event_item['url'] = elgg_get_site_url().'event_calendar/view_light_box/'.$event->guid;
			}
		
			$event_array[] = $event_item;
		}
	}
	
	$json_events_string = json_encode($event_array);
	return $json_events_string;
}

// right now this does not return repeated events in sorted order, so repeated events only really work properly for the full calendar
// TODO: find another solution for displaying repeated events

function event_calendar_flatten_event_structure($events) {
	$flattened = array();
	$guids = array();
	foreach($events as $e) {
		$this_event = $e['event'];
		$guid = $this_event->guid;
		if (!in_array($guid,$guids)) {
			$guids[] = $guid;
			$flattened[] = $this_event;
		}
	}
	return $flattened;
}

function event_calendar_queue_reminders() {
	// game plan - get all events up to 60 days ahead
	// with no reminder sent
	// compute reminder period
	// if <= current time, set reminder_queued flag and queue the
	// notification message using the message_queue plugin
	if (elgg_plugin_exists('message_queue')) {
		$now = time();
		// oops - this does not work for repeated events
		// need extra stuff for that
		/*$options = array(
			'type' => 'object',
			'subtype' => 'event_calendar',
			'metadata_name_value_pairs' => array(
				array('name' => 'reminder_queued', 'value' => 'no'),
				array('name' => 'send_reminder', 'value' => 1),
				array('name' => 'start_date', 'value' => $now + 60*24*60*60, 'operand' => '>='),
			),
			'limit' => 0,
		);
		$events = elgg_get_entities_from_metadata($options);
		*/
		$event_list = event_calendar_get_events_between($now,$now + 60*24*60*60,FALSE,0);

		foreach($event_list as $es) {
			$e = $es['event'];
			if ($e->send_reminder) {
				$reminder_period = 60*$e->reminder_interval*$e->reminder_number;
				if ($e->repeats) {
					// repeated events require more complex handing
					foreach($es['data'] as $d) {
						// if event falls in the reminder period
						if ($d->start_time - $reminder_period >= $now) {
							// and the reminder has not already been queued
							if (!event_calendar_repeat_reminder_logged($e,$d->start_time)) {
								// set the reminder queued flag
								event_calendar_repeat_reminder_log($e,$d->start_time);
								// queue the reminder for sending
								event_calendar_queue_reminder($e);
							}
							break;
						}
					}
				} else {
					// if this is just a normal non-repeated event, then we just need to set a flag and queue the reminder
					if (($e->reminder_queued != 'yes') && ($e->start_date - $now <= $reminder_period)) {
						$e->reminder_queued = 'yes';
						event_calendar_queue_reminder($e);
					}
				}
			}			
		}
	}
}

function event_calendar_repeat_reminder_log($e,$start) {
	// this simple log just uses annotations on the event
	// TODO - remove log entries for past events
	create_annotation($e->guid, 'repeat_reminder_log_item', $start, '',0,ACCESS_PUBLIC);	
}

function event_calendar_repeat_reminder_logged($e,$start) {
	$options = array(
		'guid' => $e->guid,
		'annotation_name' => 'repeat_reminder_log_item',
		'annotation_value' => $start,
		'limit' => 1
	);
	
	if (elgg_get_annotations($options)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function event_calendar_queue_reminder($e) {
	elgg_load_library('elgg:message_queue');
	$subject = elgg_echo('event_calendar:reminder:subject',array($e->title));
	$time_string = event_calendar_get_formatted_time($e);
	$body = elgg_echo('event_calendar:reminder:body',array($e->title,$time_string,$e->getURL()));
	$m = message_queue_create_message($subject,$body);
	if ($m) {
		$users = event_calendar_get_users_for_event($e->guid,0);
		foreach($users as $u) {
			message_queue_add($m->guid,$u->guid);
		}
		message_queue_set_for_sending($m->guid);
	}
}

/*function event_calendar_create_bbb_conf($event) {
	$bbb_security_salt = elgg_get_plugin_setting('bbb_security_salt','event_calendar');
	$bbb_server_url = rtrim(elgg_get_plugin_setting('bbb_server_url','event_calendar'), '/') . '/';
	if ($bbb_security_salt) {
		$day_in_minutes = 60*24;
		$now = time();
		// fix duration bug
		# $duration = (int)(($event->real_end_time-$event->start_date)/60)+$day_in_minutes;
		$duration = (int)(($event->real_end_time-$now)/60)+$day_in_minutes;
		$title = urlencode($event->title);
		$params = "name=$title&meetingID={$event->guid}&duration=$duration";
		$checksum = sha1('create'.$params.$bbb_security_salt);
		$params .= "&checksum=$checksum";
		
		// create curl resource
	    $ch = curl_init();
	
	    // set url
	    curl_setopt($ch, CURLOPT_URL, $bbb_server_url.'api/create?'.$params);
	
	    //return the transfer as a string
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	    // $output contains the output string
	    $output = curl_exec($ch);
	
	    // close curl resource to free up system resources
	    curl_close($ch);
	    
	    #error_log("BBB create request:");
	    #error_log($bbb_server_url.'api/create?'.$params);
		
	    #error_log("BBB create response:");
	    #error_log($output);
	    
		$xml = new SimpleXMLElement($output);
		if ($xml->returncode == 'SUCCESS') {
			$event->bbb_attendee_password = (string) $xml->attendeePW;
			$event->bbb_moderator_password = (string) $xml->moderatorPW;
		} else {
			register_error(elgg_echo('event_calendar:bbb_create_error',array($xml->message)));
		}
	} else {
		register_error(elgg_echo('event_calendar:bbb_settings_error'));
	}
}*/

// utility function for BBB api calls
function event_calendar_bbb_api($api_function,$params=NULL) {
	
	$bbb_security_salt = elgg_get_plugin_setting('bbb_security_salt','event_calendar');
	$bbb_server_url = rtrim(elgg_get_plugin_setting('bbb_server_url','event_calendar'), '/') . '/';
	if ($bbb_security_salt) {
		if (isset($params) && is_array($params) && count($params) > 0) {
			$query = array();
			foreach($params as $k => $v) {
				$query[] = $k.'='.rawurlencode($v);
			}
			$qs = implode('&',$query);
		} else {
			$qs = '';
		}
		$checksum = sha1($api_function.$qs.$bbb_security_salt);
		if ($qs) {
			$qs .= "&checksum=$checksum";
		}
		
		// create curl resource
	    $ch = curl_init();
	
	    // set url
	    curl_setopt($ch, CURLOPT_URL, $bbb_server_url.'api/'.$api_function.'?'.$qs);
	
	    //return the transfer as a string
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	    // $output contains the output string
	    $output = curl_exec($ch);
	
	    // close curl resource to free up system resources
	    curl_close($ch);
	    
	    error_log("BBB api call: ".$api_function);
	    error_log(print_r($params,TRUE));		
	    error_log("BBB response: \n".$output);
		return $output;
	} else {
		return FALSE;
	}		
}

function event_calendar_create_bbb_conf($event) {
	$day_in_minutes = 60*24;
	$now = time();
	// fix duration bug
	# $duration = (int)(($event->real_end_time-$event->start_date)/60)+$day_in_minutes;
	$duration = (int)(($event->real_end_time-$now)/60)+$day_in_minutes;
	if ($duration > 0) {
		$title = urlencode($event->title);
		$output = event_calendar_bbb_api('create',array('meetingID'=>$event->guid,'name'=>$title,'duration'=>$duration));
		if ($output) {	    
			$xml = new SimpleXMLElement($output);
			if ($xml->returncode == 'SUCCESS') {
				$event->bbb_attendee_password = (string) $xml->attendeePW;
				$event->bbb_moderator_password = (string) $xml->moderatorPW;
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

// checks to see if a BBB conference is actually running
function event_calendar_is_conference_running($event) {
	$output = event_calendar_bbb_api('isMeetingRunning',array('meetingID'=>$event->guid));	
	if (!$output) {
		return FALSE;
	} else {
		$xml = new SimpleXMLElement($output);
		if ($xml->returncode == 'SUCCESS' && $xml->running == 'true') {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

// checks to see if a BBB conference exists
function event_calendar_conference_exists($event) {
	$output = event_calendar_bbb_api('getMeetingInfo',array('meetingID'=>$event->guid,'password'=>$event->bbb_moderator_password));	
	if (!$output) {
		return FALSE;
	} else {
		$xml = new SimpleXMLElement($output);
		if ($xml->returncode == 'SUCCESS' && $xml->meetingID == $event->guid) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

// forwards to the join link
// this function assumes that the conference is running
function event_calendar_join_conference($event) {
	forward(event_calendar_get_join_bbb_url($event));
}

function event_calendar_get_join_bbb_url($event) {
	$bbb_security_salt = elgg_get_plugin_setting('bbb_security_salt','event_calendar');
	$bbb_server_url = rtrim(elgg_get_plugin_setting('bbb_server_url','event_calendar'), '/') . '/';
	$user = elgg_get_logged_in_user_entity();
	$full_name = urlencode($user->name);
	if ($event->canEdit()) {
		$password = urlencode($event->bbb_moderator_password);
	} else {
		$password = urlencode($event->bbb_attendee_password);
	}
	$params = "fullName=$full_name&meetingID={$event->guid}&userID={$user->username}&password=$password";
	$checksum = sha1('join'.$params.$bbb_security_salt);
	$params .= "&checksum=$checksum";
	$url = $bbb_server_url.'api/join?'.$params;
	return $url;
}

// returns TRUE if the given user can add an event to the given calendar
// if group_guid is 0, this is assumed to be the site calendar
function event_calendar_can_add($group_guid=0,$user_guid=0) {
	if (!$user_guid) {
		if (elgg_is_logged_in()) {
			$user_guid = elgg_get_logged_in_user_guid();
		} else {
			return FALSE;
		}
	}
	if ($group_guid) {
		if (!event_calendar_activated_for_group($group_guid)) {
			return FALSE;
		}
		$group = get_entity($group_guid);
		if (elgg_instanceof($group,'group')) {
			$group_calendar = elgg_get_plugin_setting('group_calendar', 'event_calendar');
			if (!$group_calendar || $group_calendar == 'members') {
				return $group->canWriteToContainer($user_guid);				
			} else if ($group_calendar == 'admin') {
				if ($group->canEdit($user_guid)) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			return FALSE;
		}
	} else {		
		$site_calendar = elgg_get_plugin_setting('site_calendar', 'event_calendar');
		if (!$site_calendar || $site_calendar == 'admin') {
			// only admins can post directly to the site-wide calendar
			return elgg_is_admin_user($user_guid);
		} else if ($site_calendar == 'loggedin') {
			// any logged-in user can post to the site calendar
			return TRUE;
		}
	}

	return FALSE;
}
