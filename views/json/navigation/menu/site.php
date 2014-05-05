<?php
/**
 * Default menu
 *
 * @uses $vars['name']                 Name of the menu
 * @uses $vars['menu']                 Array of menu items
 * @uses $vars['class']                Additional CSS class for the menu
 * @uses $vars['item_class']           Additional CSS class for each menu item
 * @uses $vars['show_section_headers'] Do we show headers for each section?
 */

global $jsonexport;

$default_items = elgg_extract('default', $vars['menu'], array());
foreach($default_items as $item){
	$return['name'] = $item->getName();
	$return['title'] = $item->getData('title');
	$return['text'] = $item->getToolTip() ?: $item->getText();
	$return['href'] = $item->getHref();
	$jsonexport[] = $return;
}
