<?php
 
  $num_items = $vars['entity']->num_items;
  if (!isset($num_items)) $num_items = 10;
 
  $widget_context_mode = $vars['entity']->widget_context_mode;
  if (!isset($widget_context_mode)) $widget_context_mode = 'search';
  elgg_set_context($widget_context_mode);
 
  $widget_datas = elgg_list_entities(array(
		'type'=>'group',
		'limit'=>$num_items,
		'full_view' => false,
		'view_type_toggle' => false,
		'pagination' => false));
	
   

echo $widget_datas;
?>       

