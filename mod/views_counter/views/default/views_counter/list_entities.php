<?php
	/**
	 * @file views/default/views_counter/list_entities.php
	 * @brief List entities in the context of views counter plugin
	 */

	$entity_type = ($vars['entity_type']) ? ($vars['entity_type']) : 'users';
	$offset = get_input('offset');
	$limit = 40;

	$options['offset'] = $offset;
	$options['limit'] = $limit;
	
	// Listing entities
	if ($entity_type != 'user' && $entity_type != 'group') {
		$options['type'] = 'object';
		$options['subtypes'] = $entity_type;
	} else {
		if($entity_type == 'user') {
			$options['type'] = 'user';
		} else {
			$options['type'] = 'group';
		}
	}
	$entities = get_entities_by_views_counter($options);
	$options['count'] = true;
	$count = elgg_get_entities($options);

	$nav = elgg_view('navigation/pagination',array(
		'base_url' => current_page_url(),
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
	));	
	echo $nav;

	$left_side = true;
	$left_column = '';
	$right_column = '';
	foreach($entities as $entity) {
		if ($left_side) {
			$left_side = false;
			$left_column .= elgg_view('views_counter/entity_listing_view',array('entity'=>$entity));			
		} else {
			$left_side = true;
			$right_column .= elgg_view('views_counter/entity_listing_view',array('entity'=>$entity));
		}
	}
?>

<div class="views_counter_admin_page">
	<div class="views_counter_left_column">
		<table>
			<tr>
				<th class="guid_column align_center"><h4><?php echo elgg_echo('views_counter:guid'); ?></h4></th>
				<th class="title_column align_center"><h4><?php echo elgg_echo('views_counter:title_or_name'); ?></h4></th>
				<th class="counter_column align_center"><h4><?php echo elgg_echo('views_counter:views'); ?></h4></th>
			</tr>
			<?php echo $left_column; ?>
		</table>
	<div class="clearfloat"></div>
	</div>
	<div class="views_counter_right_column">
		<table>
			<tr>
				<th class="guid_column align_center"><h4><?php echo elgg_echo('views_counter:guid'); ?></h4></th>
				<th class="title_column align_center"><h4><?php echo elgg_echo('views_counter:title_or_name'); ?></h4></th>
				<th class="counter_column align_center"><h4><?php echo elgg_echo('views_counter:views'); ?></h4></th>
			</tr>
			<?php echo $right_column; ?>
		</table>
	<div class="clearfloat"></div>
	</div>
	<div class="clearfloat"></div>
</div>

<?php echo $nav; ?>