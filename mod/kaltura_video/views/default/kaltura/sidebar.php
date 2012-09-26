<?php
elgg_push_context('sidebar');
$options = array('annotation_names' => 'thumbs:up', 'types' => 'object', 'subtypes' => 'kaltura_video', 'limit' => 5);
$entities = elgg_get_entities_from_annotation_calculation($options);

$content = elgg_view_entity_list($entities);

echo elgg_view_module('aside', null, $content, array('class'=>'sidebar'));

elgg_pop_context();