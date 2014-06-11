<?php
/**
 * River item footer
 *
 * @uses $vars['item'] ElggRiverItem
 * @uses $vars['responses'] Alternate override for this item
 */

// allow river views to override the response content
$responses = elgg_extract('responses', $vars, false);
if ($responses) {
	echo $responses;
	return true;
}

$item = $vars['item'];

echo elgg_view_menu('river', array(
	'item' => $item,
	'sort_by' => 'priority',
	'class' => 'minds-menu elgg-menu-hz',
));

/**
 * Allow for the plugable comments system
 */
echo elgg_view('river/elements/comments', $vars);
