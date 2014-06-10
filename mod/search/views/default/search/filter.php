<?php
$path = elgg_get_site_url() . 'search/?q=' . get_input('q');

$categories = elgg_get_site_entity()->categories;

if(!$categories){
	$categories = array();
}
foreach($categories as $category){
	elgg_register_menu_item('filter', array(
		'name'=>$category, 
		'text' => elgg_echo($category),
		'href' => "$path&category=$category",
		'selected' => $category
	));
}

echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
