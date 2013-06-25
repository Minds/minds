<?php
/**
 * Site navigation menu
 *
 * @uses $vars['menu']['default']
 */

$items = elgg_extract('default', $vars['menu'], array());
$class = elgg_extract('class', $vars);

if(elgg_get_context() == 'channel'){
	$label = elgg_get_page_owner_entity()->name;
} else {
	$label = '';
}

echo '<div class="elgg-menu-channel-elements ' . $class . '">';


	echo elgg_view('output/url', array(
		'href' => '#',
		'text' => $label,
		'class' => 'elgg-button elgg-button-channel-elements',
		'is_trusted' => true,
	));
	
	echo elgg_view('navigation/menu/elements/section', array(
		'class' => 'elgg-menu-channel-elements-dropdown', 
		'items' => $items,
	));
	
echo '</div>';
