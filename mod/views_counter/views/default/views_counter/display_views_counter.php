<?php
	/**
	 * @file views/default/views_counter/display_views_counter.php
	 * @brief Displays the number of views for an entity
	 */

	if (($vars['entity'] || $vars['entity_guid']) && ($vars['full_view'] || $vars['full'] || $vars['views_counter_full_view_override'])) {
		if(!get_input('views_counter_'.$vars['entity']->guid,'')) {
			// To make sure that the views counter will not be added for more than one time per entity for page
			set_input('views_counter_'.$vars['entity']->guid,true);
			
			$entity_guid = ($vars['entity']) ? ($vars['entity']->guid) : $vars['entity_guid'];
			$class = get_views_counter_class();
			if ((elgg_get_plugin_setting('views_counter_container_id','views_counter')) || (elgg_get_plugin_setting('display_views_counter','views_counter')=='no')) {
				$style = ' style="display: none" ';
			}
			
			// Include the js code for views counter
			echo elgg_view('js/views_counter',$vars);
?>
			<span id="views_counter" <?php echo $style; ?> class="<?php echo $class; ?>">
			<?php
				if (elgg_is_admin_logged_in()) {
			?>
					<a href="<?php echo $vars['url']; ?>views_counter/views_statistics/<?php echo $entity_guid; ?>">
			<?php		
				}
				
				echo get_views_counter($vars['entity']->guid).' '.elgg_echo('views_counter:views');
				
				if (elgg_is_admin_logged_in()) {
			?>
					</a>
			<?php 
				}
			?>
			</span>
<?php 
		}
	}
?>