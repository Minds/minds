<?php
/**
 * Site navigation menu
 *
 * @uses $vars['menu']['default']
 * @uses $vars['menu']['more']
 */

$default_items = elgg_extract('default', $vars['menu'], array());
$more_items = elgg_extract('more', $vars['menu'], array());
$all_items = array_merge($default_items, $more_items);

echo '<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">Filter <span class="caret"></span></a><ul class="dropdown-menu">';
foreach ($all_items as $menu_item) {
	echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
}

echo '</ul>';
