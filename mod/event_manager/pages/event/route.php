<div id="google_maps" style="width: 500px; height: 425px; overflow:hidden;">
	<div id="map_canvas" style="width: 500px; height: 300px;"></div>
	<?php 
	
	$form_body .= 	'<label>'.elgg_echo('from').': *</label>'.elgg_view('input/text', array('name' => 'address_from', 'id'=> 'address_from')).'<br />';
	$form_body .= 	'<label>'.elgg_echo('to').': </label><br /><span id="address_to">'.get_input('from').'</span><br />';
	
	$form_body .= 	'<a style="display: none;" target="_blank" href="" id="openRouteLink">google maps</a>';
	
	$form_body .= 	elgg_view('input/submit', array('name' => 'address_route_search', 'id' => 'address_route_search', 'type' => 'button', 'value' => elgg_echo('calculate_route'))).'&nbsp';
	
	
	echo elgg_view('input/form', array(	'id' 	=> 'event_manager_address_route_search', 
										'name' 	=> 'event_manager_address_route_search',
										'body' 			=> $form_body));	
	
	?>
</div>