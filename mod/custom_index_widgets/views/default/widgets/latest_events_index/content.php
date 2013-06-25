<?php 
	
	require_once($CONFIG->pluginspath.'event_calendar/models/model.php');
	
	if(!function_exists('getLastDayOfMonth')){
		function getLastDayOfMonth($month,$year) {
			return idate('d', mktime(0, 0, 0, ($month + 1), 0, $year));
		}
	}

	$object_type ='event_calendar';
  
	$num_items = $vars['entity']->num_items;
	if (!isset($num_items))
	    $num_items = 10;
	
	$widget_group = $vars["entity"]->widget_group;
	if (!isset($widget_group))
	    $widget_group = 0;
	    
	$mode = $vars['mode']->mode;
	if (!isset($mode))
	    $mode = "month";
	
	$site_categories = $vars['config']->site->categories;
	$widget_categorie = $vars['entity']->widget_categorie;
	$widget_context_mode = $vars['entity']->widget_context_mode;
	if (!isset($widget_context_mode))
	    $widget_context_mode = 'search';
	
	elgg_set_context($widget_context_mode);
	

	$original_start_date = date('Y-m-d');
	$day = 60*60*24;
	$week = 7*$day;
	$month = 31*$day;
	

	if ($mode == "day") {
		$start_date = $original_start_date;
		$end_date = $start_date;
		$start_ts = strtotime($start_date);
		$end_ts = strtotime($end_date)+$day-1;
	} else if ($mode == "week") {

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
	} else if ($mode == "week") {
		$end_ts = $start_ts + 6*$day;
	} else {
		$end_ts = strtotime($end_date);
	}
	
	$count = event_calendar_get_events_between($start_ts,$end_ts,true,$num_items,0,$widget_group,'-');
	$events = event_calendar_get_events_between($start_ts,$end_ts,false,$num_items,0,$widget_group,'-');
	//$widget_datas= $event_list = elgg_view_entity_list($events, $count, 0, $num_items, false, false);
	
	$options = array(
			'list_class' => 'elgg-list-entity',
			'full_view' => FALSE,
			'pagination' => TRUE,
			'list_type' => 'listing',
			'list_type_toggle' => FALSE,
			'offset' => $vars['offset'],
			'limit' => $vars['limit'],
		);
	$widget_datas = elgg_view_entity_list($events, $options);
	echo $widget_datas;
?>

