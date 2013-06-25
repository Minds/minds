<div class="contentWrapper"> 
<?php

	$num_items = $vars['entity']->num_items;
  	if (!isset($num_items)) $num_items = 10;
  
  	$widget_group = $vars["entity"]->widget_group;
  	if (!isset($widget_group)) $widget_group = ELGG_ENTITIES_ANY_VALUE;
	
	if ($widget_group != 0){
		$album = elgg_get_entities("object", 'album', $widget_group, "", 1, 0, false);
		$album_guid = $album[0]->getGUID();
		$entities = elgg_get_entities("object", "image", $album_guid, '', 999);
		elgg_set_context('front');
		$widgetdatas = elgg_view_entity_list($entities);
	}else{
		$widgetdatas = tp_get_latest_photos($num_items, 0);
	}

	echo '<div class="icon_latest">';
	echo $widgetdatas;
	echo '</div>';

?>
</div>