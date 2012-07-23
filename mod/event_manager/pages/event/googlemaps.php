<?php

$guid = get_input('guid');

?>
<div id="google_maps" style="width: 800px; height: 700px;">
	<div id="map_canvas" style="width: 800px; height: 600px;"></div>
	<?php
	
	$location = $event->location;
	$form_body .= '<label>'.elgg_echo('event_manager:event:edit:maps_address').'</label>'.elgg_view('input/text', array('name' => 'address_search', 
																														'id'=> 'address_search',
																														'value' => $location));
	
	$form_body .= elgg_view('input/submit', array('class' => "elgg-button-action", 'name' => 'address_search_submit', 'value' => elgg_echo('search'))).'&nbsp';
	$form_body .= elgg_view('input/button', array('class' => "elgg-button-submit", 'name' => 'address_search_save', 'id'=> 'address_search_save', 'value' => elgg_echo('save')));
	
	
	echo elgg_view('input/form', array(	'id' 	=> 'event_manager_address_search', 
										'name' 	=> 'event_manager_address_search',
										'body' 			=> $form_body));
	
	?>
</div>
<?php 
