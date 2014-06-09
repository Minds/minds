<?php
/**
 * Site navigation menu
 *
 * @uses $vars['menu']['default']
 * @uses $vars['menu']['more']
 */

$default_items = elgg_extract('default', $vars['menu'], array());
$more_items = elgg_extract('more', $vars['menu'], array());

//$minds = array_merge($default_items, $more_items);
$minds = $default_items;
echo '<ul class="menu global-menu elgg-menu elgg-menu-site elgg-menu-site-default clearfix">';
foreach ($default_items as $menu_item) {
	echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item, 'menu_type'=>'site'));
}
foreach ($more_items as $menu_item) {
//	echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
}

if ($minds) {
	echo '<li class="elgg-more">';

	$more = elgg_echo('more');
	echo "<a href=\"#\" class=\"\">Community</a>";
	
	echo elgg_view('navigation/menu/elements/section', array(
		'class' => 'elgg-menu elgg-menu-site elgg-menu-site-more', 
		'items' => $minds,
	));
	
	echo '</li>';
}
echo '</ul>';

