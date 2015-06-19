<?php
$path = elgg_get_site_url() . 'search/?q=' . get_input('q');
$path= htmlspecialchars($path);

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
?>

<ul class="search-filter">

	<li><a href="<?= elgg_get_site_url() ?>search?q=<?= get_input('q') ?>"  class="<?= !get_input('subtype') && !get_input('type') ? 'active' : '' ?> ">All</a></li>
	<li><a href="<?= elgg_get_site_url() ?>search?q=<?= get_input('q') ?>&type=user" class="<?= get_input('type') == 'user' ? 'active' : '' ?> ">Channels</a></li>
	<li><a href="<?= elgg_get_site_url() ?>search?q=<?= get_input('q') ?>&subtype=blog" class="<?= get_input('subtype') == 'blog' ? 'active' : '' ?> ">Blogs</a></li>
	<li><a href="<?= elgg_get_site_url() ?>search?q=<?= get_input('q') ?>&subtype=video" class="<?= get_input('subtype') == 'video' ? 'active' : '' ?> ">Videos</a></li>
	<li><a href="<?= elgg_get_site_url() ?>search?q=<?= get_input('q') ?>&subtype=image" class="<?= get_input('subtype') == 'image' ? 'active' : '' ?> ">Images</a></li>
	<li><a href="<?= elgg_get_site_url() ?>search?q=<?= get_input('q') ?>&type=activity" class="<?= get_input('type') == 'activity' ? 'active' : '' ?> ">News</a></li>

</ul>
