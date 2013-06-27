<?php
/**
 * Site navigation menu
 *
 * @uses $vars['menu']['default']
 * @uses $vars['menu']['more']
 */

$default_items = elgg_extract('default', $vars['menu'], array());
$more_items = elgg_extract('more', $vars['menu'], array());
echo "<div class='elgg-menu-site'>";
echo "<div class='navbar'>";
echo "<div class='navbar-inner'>";
echo '<ul class="nav">';
foreach ($default_items as $menu_item) {
	echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
}
if ($more_items) {
	echo '<li class="dropdown">';
	$more = elgg_echo('more');
	echo "<a href=\"#\" class='dropdown-toggle' data-toggle='dropdown'>$more</a>";
	echo elgg_view('navigation/menu/elements/section', array(
		'class' => 'elgg-menu elgg-menu-site elgg-menu-site-more', 
		'items' => $more_items,
	));
	echo '</li>';
}
echo '</ul>';
echo "</div>";
echo "</div>";
echo "</div>";