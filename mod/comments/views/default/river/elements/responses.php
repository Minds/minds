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

if($item->action_type == 'create' || $item->action_type == 'feature'){
	echo \minds\plugin\comments\comments::display($item->getObjectEntity());
}




