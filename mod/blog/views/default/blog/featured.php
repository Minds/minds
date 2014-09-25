<?php
$featured_blogs = minds_get_featured(null, 4);
if($featured_blogs){
	$featured_blogs = elgg_view_entity_list($featured_blogs,  array('full_view'=>false, 'sidebar'=>true, 'class'=>'blog-sidebar', 'pagination'=>false, 'masonry'=>false));
	echo elgg_view_module('aside', elgg_echo('blog:featured'), $featured_blogs, array('class'=>'blog-sidebar'));	
}