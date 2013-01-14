<?php
/**
 * Minds Archive featured. 
 * 
 * NB. Limited to videos for time being.
 */
 //temp disable until we optimize @MH
 return true;
elgg_push_context('sidebar');
$options = array('types' => 'object', 'subtypes' => 'kaltura_video', 'metadata_name_value_pairs'=> array('name' => 'featured','value'=>true ),'limit' => 5);
$entities = elgg_get_entities_from_metadata($options);

if($entities){
	$content = elgg_view_entity_list($entities);
} else {
	$content = 'Featured videos coming soon';
}

echo elgg_view_module('aside', elgg_echo('archive:featured:title'), $content, array('class'=>'sidebar'));

elgg_pop_context();
