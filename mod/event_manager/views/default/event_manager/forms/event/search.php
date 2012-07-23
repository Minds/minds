<?php 

	$form_toggle = '<a href="javascript: void(0);" id="event_manager_event_search_advanced_enable"><span>'.elgg_echo('event_manager:list:advancedsearch').'</span><span style="display:none;">'.elgg_echo('event_manager:list:simplesearch').'</span></a>';
	
	$form_body .= $form_toggle;
	$form_body .= elgg_view('input/hidden', array('name' => 'search_type', 'id' => 'search_type', 'value' => 'list'));
	$form_body .= elgg_view('input/hidden', array('name' => 'container_guid', 'value' => elgg_get_page_owner_guid()));
	$form_body .= elgg_view('input/text', array('value' => '', 'name' => 'search', 'id' => 'search', 'class' => 'event_manager_event_list_search_input')).'&nbsp;';
	
	$form_body .= elgg_view('input/submit', array('value' => elgg_echo('search')));
	
	$form_body .= "<span id='past_events'>";
	$form_body .= elgg_view('input/checkboxes', array('name' => 'past_events', 'value' => 0, 'options' => array(elgg_echo('event_manager:list:includepastevents')=>'1')));
	$form_body .= "</span>";
	
	$form_body .= '<div id="event_manager_event_search_advanced_container">';
	$form_body .= elgg_view('input/hidden', array('name' => 'advanced_search', 'id' => 'advanced_search', 'value' => 0));
	
	$form_body .= elgg_echo('event_manager:edit:form:start_day:from').': '.elgg_view('input/date', array('name' => 'start_day', 'id' => 'start_day', 'value' => date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY,''))).'&nbsp;';
	$form_body .= elgg_echo('event_manager:edit:form:start_day:to').': '.elgg_view('input/date', array('name' => 'end_day', 'id' => 'end_day', 'value' => date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY,''))).'<br /><br />';

	$form_body .= "<div>";
	if($region_options = event_manager_event_region_options()){
		$form_body .= elgg_echo('event_manager:edit:form:region') . ': ' . elgg_view('input/dropdown', array('name' => 'region', 'value' => $fields["region"], 'options' => $region_options)).' ';
	}
	
	if($type_options = event_manager_event_type_options())	{
		$form_body .= elgg_echo('event_manager:edit:form:type') . ': ' . elgg_view('input/dropdown', array('name' => 'event_type', 'value' => $fields["event_type"], 'options' => $type_options));
	}
	
	$form_body .= "</div>";
	
	if(elgg_is_logged_in()){
		$form_body .= elgg_view('input/checkboxes', array('id' => 'attending', 'name' => 'attending', 'value' => 0, 'options' => array(elgg_echo('event_manager:list:meattending')=>'1')));
		$form_body .= elgg_view('input/checkboxes', array('id' => 'owning', 'name' => 'owning', 'value' => 0, 'options' => array(elgg_echo('event_manager:list:owning')=>'1')));
		$form_body .= elgg_view('input/checkboxes', array('id' => 'friendsattending', 'name' => 'friendsattending', 'value' => 0, 'options' => array(elgg_echo('event_manager:list:friendsattending')=>'1')));
	}
	
	$form_body .= '</div>';
	
	$form = elgg_view('input/form', array(	'id' 	=> 	'event_manager_search_form', 
											'name' 	=> 'event_manager_search_form', 
											'action' 		=> $vars['url'].'action/event_manager/event/search',
											'body' 			=> $form_body));
	
	echo elgg_view_module("main", "" , $form);