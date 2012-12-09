<?php
/**
 * Site navigation menu
 *
 * @uses $vars['menu']['default']
 */

$items = elgg_extract('default', $vars['menu'], array());

echo '<div class="elgg-menu-channel-elements">';


	echo elgg_view('output/url', array(
		'href' => '#',
		'text' => elgg_echo('channel:elements'),
		'class' => 'elgg-button elgg-button-channel-elements',
		'is_trusted' => true,
	));
	
	echo elgg_view('navigation/menu/elements/section', array(
		'class' => 'elgg-menu-channel-elements-dropdown', 
		'items' => $items,
	));
	
echo '</div>';
