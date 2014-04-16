<?php
/**
 * JSON river item view
 *
 * @uses $vars['item']
 */

global $jsonexport;
if (!isset($jsonexport['activity'])) {
	$jsonexport['activity'] = array();
}

/*$item = $vars['item'];
if (elgg_view_exists($item->view, 'default')) {
	$item->string = elgg_view('river/elements/summary', array('item' => $item), FALSE, FALSE, 'default');
}*/
$item = $vars['item']->export();

$jsonexport['activity'][] = $item;
