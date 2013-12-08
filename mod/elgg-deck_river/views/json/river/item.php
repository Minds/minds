<?php
/**
 * JSON river item view
 *
 * @uses $vars['item']
 */

global $jsonexport;

$mention = elgg_extract('mention', $vars, false);

if (elgg_view_exists($vars['item']->view, 'default')) {
	$vars['item']->summary = elgg_view('river/elements/summary', array('item' => $vars['item']), FALSE, FALSE, 'default');
	$object = $vars['item']->getObjectEntity();

	if ($mention) {
		$vars['item']->message = deck_river_highlight_mention(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $object->description), $mention);
	} else {
		$vars['item']->message = elgg_get_excerpt(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $object->description), 140);
	}

}

$jsonexport['results'][] = $vars['item'];