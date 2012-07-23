<?php 
$event = $vars['entity'];
?>

<script type="text/javascript">

	$(function() {
		initMaps('map_canvas');
		event_manager_geocoder.getLatLng('<?php echo $event->location;?>', function(gpoint) {
			if(gpoint) {
				event_manager_gmap.setCenter(gpoint, 15);
				addMarker(gpoint, true);
			}
		});
		
		$('#event_manager_address_route_search').submit(function(e) {
			frmAddress = $('#address_from').val();
			dstAddress = '<?php echo $event->location;?>';
			
			if(frmAddress == '') {
				alert('<?php echo elgg_echo('event_manager:action:event:edit:error_fields');?>');
			} else {
				link = 'http://maps.google.com/maps?f=d&source=s_d&saddr='+frmAddress+'&daddr='+dstAddress+'&hl=<?php echo get_current_language();?>';

				window.open(link);
			}
			e.preventDefault();
		});
	});

</script>
<div id="google_maps" style="width: 500px; height: 425px; overflow:hidden;">
	<div id="map_canvas" style="width: 500px; height: 300px;"></div>
	<?php 
	
	$form_body .= 	'<label>'.elgg_echo('from').': *</label>'.elgg_view('input/text', array('name' => 'address_from', 'id'=> 'address_from')).'<br />';
	$form_body .= 	'<label>'.elgg_echo('to').': </label><br />'.$event->location.'<br />';
	
	$form_body .= 	'<a style="display: none;" target="_blank" href="" id="openRouteLink">google maps</a>';
	
	$form_body .= 	elgg_view('input/submit', array('name' => 'address_route_search', 'id' => 'address_route_search', 'value' => elgg_echo('calculate_route'))).'&nbsp';
	
	
	echo elgg_view('input/form', array(	'id' 	=> 'event_manager_address_route_search', 
										'name' 	=> 'event_manager_address_route_search',
										'body' 			=> $form_body));	
	
	?>
</div>