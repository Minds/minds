<?php
	/**
	 * @file views/default/js/views_counter.php
	 * @brief Javascript code for the plugin views counter
	 */

	$views_counter_js = get_input('views_counter_js');
	if (!$views_counter_js) {
?>
		<script>
			addLoadEvent(attach_views_counter);

			function addLoadEvent(func) {
				var oldfunc = window.onload;
				if (typeof oldfunc != 'function') {
					window.onload = func;
				} else {
					window.onload = function() {
						if (oldfunc) {
							oldfunc();
						}
						func();
					};
				}				
			}
			
			function attach_views_counter() {
				var views_counter_container = "<?php echo elgg_get_plugin_setting('views_counter_container_id','views_counter'); ?>";
				var parent = document.getElementById(views_counter_container);
		
				if (parent) {
					var views_counter = document.getElementById('views_counter');
					parent.appendChild(views_counter);
					views_counter.setAttribute('style','');
				}		
			}
		</script>
<?php
	} 
?>