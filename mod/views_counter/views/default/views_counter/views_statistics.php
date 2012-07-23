<?php
	/**
	 * @file views/default/views_counter/views_statistics.php
	 * @brief Displays the views statistics for one entity
	 */

	$entity = ($vars['entity']) ? ($vars['entity']) : (get_entity(get_input('entity_guid')));
	
	if ($entity) {
	    $offset = get_input('offset', 0);
	    $limit = get_input('limit', 20);
	    
	    $options = array(
	      'guid' => $vars['entity']->guid,
	      'limit' => 0,
	      'count' => TRUE,
	      'annotation_names' => array('views_counter'),
	    );
	    
	    $count = elgg_get_annotations($options);
	    
	    $options['count'] = FALSE;
	    $options['limit'] = $limit;
	    $options['offset'] = $offset;
	    // values stored as strings, need to add int in mysql to order properly
	    $options['order_by'] = '(value + 0) desc';
	    
		if ($annotations = elgg_get_annotations($options)) {
?>
			<table>
				<tr>
					<th class="id_column"><?php echo elgg_echo('views_counter:id'); ?></th>
					<th class="name_column"><?php echo elgg_echo('views_counter:name_or_title'); ?></th>
					<th class="user_name_column"><?php echo elgg_echo('views_counter:user_name'); ?></th>
					<th class="views_column"><?php echo elgg_echo('views_counter:views_by_user'); ?></th>
					<th class="first_view_column"><?php echo elgg_echo('views_counter:first_view'); ?></th>
				</tr>
				
				<?php foreach($annotations as $annotation):
                  $entity = get_entity($annotation->entity_guid);
			      $entity_name = ($entity->title) ? ($entity->title) : ($entity->name);
			      $owner = get_entity($annotation->owner_guid);
			      
			      if(!$entity){
			       continue; 
			      }
				?>
				
				<tr>
					<td class="id_column"><?php echo $annotation->id; ?></td>
					<td class="name_column">
						<a href="<?php echo $entity->getUrl(); ?>"><?php echo $entity_name; ?></a>
					</td>
					<td class="user_name_column">
		              <?php
			            if ($owner) {
		              ?>
						<a href="<?php echo $owner->getUrl(); ?>"><?php echo $owner->name; ?></a>
		              <?php
			            }
			            else {
				          echo elgg_echo('views_counter:not_loggedin');
			            }
		               ?>
					</td>
					<td class="views_column"><?php echo $annotation->value; ?></td>
					<td class="first_view_column"><?php echo elgg_view_friendly_time($annotation->time_created); ?></td>
				</tr>

				<?php endforeach; ?>
			</table>
<?php
		}
		
		echo elgg_view('navigation/pagination', array('offset' => $offset, 'limit' => $limit, 'count' => $count));
	}
