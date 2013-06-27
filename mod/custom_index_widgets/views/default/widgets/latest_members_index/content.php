<?php 
  
  $num_items = $vars['entity']->num_items;
  if (!isset($num_items)) $num_items = 10;
  $display_avatar = $vars['entity']->display_avatar;
  if (!isset($display_avatar)) $display_avatar = 'yes';
  
  $widget_datas = elgg_list_entities_from_metadata(array(
	'metadata_names' => 'icontime',
	'types' => 'user',
	'limit' => $num_items,
	'full_view' => false,
	'pagination' => false,
	'size' => 'small',
	));

echo $widget_datas;
?>        


