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

foreach ($vars['menu'] as $section => $menu_items) {
	foreach($menu_items as $item){
		$return['name'] = $item->getName();
		$return['title'] = $item->getData('title');
		$return['text'] = $item->getText();
		$return['href'] = $item->getHref();
		$jsonexport[] = $return;
	}
}
