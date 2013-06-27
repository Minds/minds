<?php 
  
  $object_type ='file';
  
  $num_items = $vars['entity']->num_items;
  if (!isset($num_items)) $num_items = 10;
  
  $widget_group = $vars["entity"]->widget_group;
  if (!isset($widget_group)) $widget_group = ELGG_ENTITIES_ANY_VALUE;
  

  $site_categories = $vars['config']->site->categories;
  $widget_categorie = $vars['entity']->widget_categorie;
  $widget_context_mode = $vars['entity']->widget_context_mode;
  if (!isset($widget_context_mode)) $widget_context_mode = 'search';
  elgg_set_context($widget_context_mode);
    
  if ($site_categories == NULL || $widget_categorie == NULL) {
    $widget_datas = elgg_list_entities(array(
		'type'=>'object',
		'subtype'=>$object_type,
		'container_guids' => $widget_group,
		'limit'=>$num_items,
		'full_view' => false,
		'view_type_toggle' => false,
		'pagination' => false));
} else {

	$widget_datas = elgg_list_entities_from_metadata(array(
		'type'=>'object',
		'subtype'=>$object_type,
		'container_guids' => $widget_group,
		'limit'=>$num_items,
		'full_view' => false,
		'view_type_toggle' => false,
		'pagination' => false,
		'metadata_name' => 'universal_categories',
		'metadata_value' => $widget_categorie,
		));
}
  
	echo $widget_datas;
?>        

