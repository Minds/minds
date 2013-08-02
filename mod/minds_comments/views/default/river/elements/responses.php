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
$type = 'river';
$pid = $item->id;

if($item->action_type == 'create' || $item->action_type == 'feature'){
	$type = 'entity';
	$pid = $item->object_guid;
}

echo elgg_view('minds_comments/bar', array(
		'type' => $type,
	    'pid' => $pid,
));
	
echo elgg_view('minds_comments/input', array(
	   'type' => $type,
		'pid' => $pid
));


