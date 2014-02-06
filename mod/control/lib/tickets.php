<?php
/**
 * Library files for tickets, from the control plugin
 */
 
/**
 * Return a list of tickets
 * @todo - allow for filters, such as tags, priority, state
 * 
 * @param $owner_guid - not required
 * @return array
 */
function control_get_page_tickets_list($owner_guid = 0){
	
	elgg_register_title_button();
	
	$params = array(
		'type'=>'object',
		'subtype'=>'control_tickets',
		'limit' => get_input('limit', 12),
		'offset' => get_input('offset', ''),
	);
	
	if($owner_guid){
		$params['owner_guid'] = get_entity($owner_guid);
	}
	
	return elgg_list_entities($params);
}

/**
 * Loads the input form
 */
function control_get_page_tickets_add(){
	return elgg_view_form('control/tickets/add');
}
/**
 * Loads the edit form
 */
function control_get_page_tickets_edit(){
	return elgg_view_form('control/tickets/edit');
}
