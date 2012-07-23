<?php
	/**
	 * @file views/default/views_counter/entity_listing_view.php
	 * @brief Displays a entity in the context of views_counter plugin
	 */
?>
<tr>
	<td class="guid_column align_center"><?php echo $vars['entity']->guid;?></td>
	<td class="title_column align_center">
		<?php $name = ($vars['entity']->name) ? ($vars['entity']->name) : $vars['entity']->title; ?>
		<a href="<?php echo $vars['url']; ?>views_counter/views_statistics/<?php echo $vars['entity']->guid; ?>"><?php echo $name; ?></a>
	</td>
	<td class="counter_column align_center"><?php echo get_views_counter($vars['entity']->guid); ?></td>
</tr>