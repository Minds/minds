<?php
/**
 * JSON river item view
 *
 * @uses $vars['item']
 */

global $jsonexport;
$jsonexport['called'] = 'abc';
if (!isset($jsonexport['activity'])) {
	$jsonexport['activity'] = array();
}

$item = $vars['item'];
if (elgg_view_exists($item->view, 'default')) {
	$item->string = elgg_view('river/elements/summary', array('item' => $item), FALSE, FALSE, 'default');
}
//$item = $item->export();

$jsonexport['activity'][] = $vars['item'];
