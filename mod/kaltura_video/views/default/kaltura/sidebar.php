<?php
elgg_push_context('sidebar');

$featured = minds_get_featured('kaltura_video', 5);
$content = elgg_view_entity_list($featured);

echo elgg_view_module('aside', elgg_echo('archive:featured:title'), $content, array('class'=>'sidebar'));

elgg_pop_context();