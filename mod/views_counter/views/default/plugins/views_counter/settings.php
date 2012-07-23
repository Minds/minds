<?php
	/**
	 * @file views/default/settings/views_counter/edit.php
	 * @brief Shows the settings form for the views_counter plugin
	 */
?>

<h3><?php echo elgg_echo('views_counter:add_views_counter'); ?></h3>
<span class="reduced_text"><?php echo elgg_echo('views_counter:add_counter_explanation'); ?></span>

<input type="hidden" name="params[add_views_counter][]" value="null" />

<table>
	<?php
		// Get the previous selected type for add views counter
		$add_views_counter_values = unserialize(elgg_get_plugin_setting('add_views_counter','views_counter'));
		
		// Get the valid types that the views counter may be added on
		$valid_types = get_valid_types_for_views_counter();
		
		$column = 0;
		foreach($valid_types as $entity_type) {
			// Add a <tr> element for each line starting
			if (!$column) {
					echo '<tr>';
			}
			
			// Check if there is some translation for this entity type
			$entity_type_text = (('item:object:'.$entity_type) != elgg_echo('item:object:'.$entity_type)) ? (elgg_echo('item:object:'.$entity_type)) : ($entity_type);
			
			// Check if this type was saved in the plugin settings database
			$checked = (in_array($entity_type,$add_views_counter_values)) ? (' checked="checked" ') : '';

			// Create one column for each type
			echo '<td class="settings_column">';
			echo '<label><input '.$checked.' name="params[add_views_counter][]" type="checkbox" value="'.$entity_type.'" />'.$entity_type_text.'</labe>';
			echo '</td>';
			
			$column++;
			// If there is 3 columns then lets create another line for the table
			if ($column==3) {
				echo '</tr>';
				$column = 0;
			}
		}
	?>
</table>

<br />
<div>
	<div class="views_counter_settings_left">
		<h3><?php echo elgg_echo('views_counter:container_id'); ?></h3>
		<?php
			// Setting container ID input
			$value = elgg_get_plugin_setting('views_counter_container_id','views_counter');
			echo elgg_view('input/text',array('name'=>'params[views_counter_container_id]','value'=>$value,'class'=>'container_id_input'));
		?>
		<br />
		<span class="reduced_text"><?php echo elgg_echo('views_counter:container_id_explanation'); ?></span>
		<br />
		<br />
		
		<h3><?php echo elgg_echo('views_counter:float_direction'); ?></h3>
		<?php 
			$value = elgg_get_plugin_setting('float_direction','views_counter');
			if (!$value) {
				$value = 'right';
				elgg_set_plugin_setting('float_direction','right','views_counter');
			}	
			$options = array(
				elgg_echo('views_counter:left')=>'float_left',
				elgg_echo('views_counter:right')=>'float_right',
				elgg_echo('views_counter:no_float')=>'none',
				);
			echo elgg_view('input/radio',array('name'=>'params[float_direction]','options'=>$options, 'value'=>$value));
		?>
	</div>
	
	<div class="views_counter_settings_right">
		<h3><?php echo elgg_echo('views_counter:display_views_counter'); ?></h3>
		<?php 
			$value = elgg_get_plugin_setting('display_views_counter','views_counter'); 
			if (!$value) {
				$value = 'yes';
				elgg_set_plugin_setting('display_views_counter','yes','views_counter');
			}
			$options = array(
				elgg_echo('views_counter:yes')=>'yes',
				elgg_echo('views_counter:no')=>'no',
			);
			echo elgg_view('input/radio',array('name'=>'params[display_views_counter]','options'=>$options, 'value'=>$value));
		?>
		<br />
		
		<h3><?php echo elgg_echo('views_counter:remove_class'); ?></h3>
		<?php
			$value = elgg_get_plugin_setting('remove_css_class','views_counter'); 
			if (!$value) {
				$value = 'no';
				elgg_set_plugin_setting('remove_css_class','no','views_counter');
			}
			$options = array(
				elgg_echo('views_counter:yes')=>'yes',
				elgg_echo('views_counter:no')=>'no',
			);
			echo elgg_view('input/radio',array('name'=>'params[remove_css_class]','options'=>$options, 'value'=>$value));
		?>
	</div>
	<div class="clearfloat"></div>
</div>