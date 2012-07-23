<?php 

$location = elgg_get_plugin_setting('google_maps_default_location', 'event_manager');
if(empty($location)){
	$location = "Netherlands";
}

$zoom_level = elgg_get_plugin_setting('google_maps_default_zoom', 'event_manager');
if($zoom_level == ""){
	$zoom_level = 10;
}
$zoom_level = sanitise_int($zoom_level);

?>
<script type="text/javascript">
	var EVENT_MANAGER_BASE_LOCATION = "<?php echo $location; ?>";
	var EVENT_MANAGER_BASE_ZOOM = <?php echo $zoom_level; ?>;
</script>
<?php 
